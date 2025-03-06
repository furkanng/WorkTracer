<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SecretaryController extends Controller
{
    public function index()
    {
        $secretaries = User::where('role_id', 3)->latest()->paginate(10);
        return view('admin.secretaries.index', compact('secretaries'));
    }

    public function create()
    {
        return view('admin.secretaries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $validated['role_id'] = 3; // Sekreter rolü
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.secretaries.index')
            ->with('success', 'Sekreter başarıyla oluşturuldu.');
    }

    public function edit(User $secretary)
    {
        return view('admin.secretaries.edit', compact('secretary'));
    }

    public function update(Request $request, User $secretary)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $secretary->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $secretary->update($validated);

        return redirect()->route('admin.secretaries.index')
            ->with('success', 'Sekreter bilgileri güncellendi.');
    }
} 