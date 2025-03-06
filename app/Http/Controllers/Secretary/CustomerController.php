<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('secretary.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('secretary.customers.create');
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

        return redirect()->route('secretary.customers.index')
            ->with('success', 'Müşteri başarıyla eklendi.');
    }

    public function show(Customer $customer)
    {
        return view('secretary.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('secretary.customers.edit', compact('customer'));
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

        return redirect()->route('secretary.customers.index')
            ->with('success', 'Müşteri başarıyla güncellendi.');
    }

    public function storeTransaction(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'type' => 'required|in:payment,debt',
            'amount' => 'required|numeric|min:0',
            'document_no' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        $validated['user_id'] = auth()->id();

        $customer->transactions()->create($validated);

        // Bakiyeyi güncelle
        $customer->balance = $customer->total_debt;
        $customer->save();

        return redirect()->back()->with('success', 'İşlem başarıyla kaydedildi.');
    }
} 