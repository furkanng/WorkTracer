<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PriceList;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use App\Mail\TaskStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['customer', 'taskType'])
            ->where('technician_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('technician.tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        // Yalnızca kendisine atanan görevleri görebilmeli
        if ($task->technician_id !== auth()->id()) {
            abort(403);
        }
        return view('technician.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        // Yalnızca kendisine atanan görevleri görebilmeli
        if ($task->technician_id !== auth()->id()) {
            abort(403);
        }

        $customer = Customer::query()->findOrFail($task->customer_id);

        return view('technician.tasks.edit', compact(['customer','task']));
    }

    public function storeTransaction(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'type' => 'required|in:debt,payment',
            'description' => 'nullable|string',
        ]);

        $transaction = new Transaction();
        $transaction->customer_id = $customer->id;
        $transaction->user_id = auth()->id();
        $transaction->type = $validated['type'];
        $transaction->amount = $request->type == "debt" ? $request->total_amount : $request->amount ;
        $transaction->description = $validated['description'];
        $transaction->transaction_date = now();
        $transaction->save();

        if ($validated['type'] === 'debt' && !empty($request->items)) {
            // Fatura oluştur
            $invoice = new Invoice();
            $invoice->customer_id = $customer->id;
            $invoice->task_id = request('task_id'); // Eğer task detay sayfasından geliyorsa
            $invoice->transaction_id = $transaction->id;
            $invoice->invoice_no = 'INV-' . date('Ymd') . '-' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT);
            $invoice->total_amount = $request->total_amount;
            $invoice->notes = $validated['description'];
            $invoice->type = "debt";
            $invoice->save();

            // Fatura kalemleri
            foreach ($request->items as $item) {
                $price = PriceList::findOrFail($item['price_id']);
                $total = $price->unit_price * $item['quantity'];

                $invoice->items()->create([
                    'price_id' => $price->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $price->unit_price,
                    'total' => $total
                ]);
            }

            // Transaction açıklamasını güncelle
            $itemDetails = collect($invoice->items)->map(function ($item) {
                $price = $item->price;
                return sprintf(
                    "%s\nBirim Fiyat: %.2f ₺\nMiktar: %.2f %s\nToplam: %.2f ₺",
                    $price->name,
                    $item->unit_price,
                    $item->quantity,
                    $price->unit,
                    $item->total
                );
            })->implode("\n\n");

            $transaction->description = "Fatura No: {$invoice->invoice_no}\n\n" . $itemDetails;
            $transaction->save();
        }

        // Müşterinin bakiyesini güncelle
        if ($validated['type'] === 'debt') {
            $customer->balance += $request->total_amount;
        } else {

            $invoice = new Invoice();
            $invoice->customer_id = $customer->id;
            $invoice->task_id = request('task_id'); // Eğer task detay sayfasından geliyorsa
            $invoice->transaction_id = $transaction->id;
            $invoice->invoice_no = 'INV-' . date('Ymd') . '-' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT);
            $invoice->total_amount = $request->amount;
            $invoice->notes = $validated['description'];
            $invoice->type = "payment";
            $invoice->save();

            $customer->balance -= $request->amount;
        }
        $customer->save();

        return redirect()->back()->with('success', 'İşlem başarıyla kaydedildi.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        // Yalnızca kendisine atanan görevleri güncelleyebilmeli
        if ($task->technician_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $oldStatus = $task->status;
        
        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        }

        $task->update($validated);

        // Admin kullanıcılarına mail gönder
        try {
            $admins = User::where('role_id', 1)->get(); // role_id 1 adminler için
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new TaskStatusUpdated($task, $oldStatus, $validated['status']));
            }
        } catch (\Exception $e) {
            report($e);
        }

        return redirect()->back()->with('success', 'Görev durumu güncellendi.');
    }
} 