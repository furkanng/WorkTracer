<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\PriceList;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'tax_office' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Müşteri başarıyla eklendi.');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'tax_office' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Müşteri başarıyla güncellendi.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', 'Müşteri başarıyla silindi.');
    }

    public function show(Customer $customer)
    {
        return view('admin.customers.show', compact('customer'));
    }

    public function storeTransaction(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'type' => 'required|in:debt,payment',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'items' => 'required_if:type,debt|array',
            'items.*.price_id' => 'required_if:type,debt|exists:price_lists,id',
            'items.*.quantity' => 'required_if:type,debt|numeric|min:0'
        ]);

        $transaction = new Transaction();
        $transaction->customer_id = $customer->id;
        $transaction->user_id = auth()->id();
        $transaction->type = $validated['type'];
        $transaction->amount = $validated['amount'];
        $transaction->description = $validated['description'];
        $transaction->transaction_date = now();
        $transaction->save();

        if ($validated['type'] === 'debt' && !empty($validated['items'])) {
            // Fatura oluştur
            $invoice = new Invoice();
            $invoice->customer_id = $customer->id;
            $invoice->task_id = request('task_id'); // Eğer task detay sayfasından geliyorsa
            $invoice->transaction_id = $transaction->id;
            $invoice->invoice_no = 'INV-' . date('Ymd') . '-' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT);
            $invoice->total_amount = $validated['amount'];
            $invoice->notes = $validated['description'];
            $invoice->save();

            // Fatura kalemleri
            foreach ($validated['items'] as $item) {
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
            $customer->balance += $validated['amount'];
        } else {
            $customer->balance -= $validated['amount'];
        }
        $customer->save();

        return redirect()->back()->with('success', 'İşlem başarıyla kaydedildi.');
    }
} 