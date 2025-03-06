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
            'type' => 'required|in:debt,payment',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'price_id' => 'required_if:type,debt|nullable|exists:price_lists,id',
            'quantity' => 'required_if:type,debt|nullable|numeric|min:0'
        ]);

        $transaction = new Transaction();
        $transaction->customer_id = $customer->id;
        $transaction->type = $validated['type'];
        $transaction->amount = $validated['amount'];
        $transaction->description = $validated['description'];

        if ($validated['type'] === 'debt' && isset($validated['price_id'])) {
            $price = PriceList::findOrFail($validated['price_id']);
            $transaction->price_id = $price->id;
            $transaction->quantity = $validated['quantity'];
            
            // Fatura detaylarını description'a ekle
            $transaction->description = sprintf(
                "%s\nMarka: %s\nBirim Fiyat: %.2f ₺\nMiktar: %.2f %s\nToplam: %.2f ₺",
                $price->name,
                $price->brand ? $price->brand->name : '-',
                $price->unit_price,
                $validated['quantity'],
                $price->unit,
                $validated['amount']
            );
        }

        $transaction->save();

        // Müşterinin bakiyesini güncelle
        if ($validated['type'] === 'debt') {
            $customer->balance += $validated['amount'];
        } else {
            $customer->balance -= $validated['amount'];
        }
        $customer->save();

        return redirect()->route('secretary.customers.show', $customer)
            ->with('success', 'İşlem başarıyla kaydedildi.');
    }
} 