<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\CustomerTransaction;
use App\Models\Task;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Task $task)
    {
        return view('technician.payments.create', compact('task'));
    }

    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'transaction_type' => 'required|in:debt,payment',
            'document_no' => 'nullable|string'
        ]);

        CustomerTransaction::create([
            'customer_id' => $task->customer_id,
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'transaction_type' => $validated['transaction_type'],
            'document_no' => $validated['document_no']
        ]);

        return redirect()->route('technician.tasks.show', $task)
            ->with('success', 'Ödeme başarıyla eklendi.');
    }

    public function edit(Task $task, CustomerTransaction $transaction)
    {
        return view('technician.payments.edit', compact('task', 'transaction'));
    }

    public function update(Request $request, Task $task, CustomerTransaction $transaction)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'transaction_type' => 'required|in:debt,payment',
            'document_no' => 'nullable|string'
        ]);

        $validated['user_id'] = auth()->id();

        $transaction->update($validated);

        return redirect()->route('technician.tasks.show', $task)
            ->with('success', 'Ödeme başarıyla güncellendi.');
    }
} 