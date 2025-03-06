<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Task;
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
            'type' => 'required|in:payment,debt',
            'amount' => 'required|numeric|min:0',
            'document_no' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        $validated['user_id'] = auth()->id(); // Kullanıcı ID'sini ekle

        $customer->transactions()->create($validated);

        // Bakiyeyi güncelle
        $customer->balance = $customer->total_debt;
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