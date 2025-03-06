<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceList;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    public function index()
    {
        $prices = PriceList::latest()->paginate(10);
        return view('admin.prices.index', compact('prices'));
    }

    public function create()
    {
        return view('admin.prices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'is_active' => 'boolean',
            'brand_id' => 'nullable|exists:brands,id'
        ]);

        PriceList::create($validated);

        return redirect()->route('admin.prices.index')
            ->with('success', 'Fiyat başarıyla eklendi.');
    }

    public function edit(PriceList $price)
    {
        return view('admin.prices.edit', compact('price'));
    }

    public function update(Request $request, PriceList $price)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'is_active' => 'boolean',
            'brand_id' => 'nullable|exists:brands,id'
        ]);

        $price->update($validated);

        return redirect()->route('admin.prices.index')
            ->with('success', 'Fiyat başarıyla güncellendi.');
    }
} 