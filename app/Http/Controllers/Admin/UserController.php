<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $technicians = User::where('role_id', 2)->latest()->paginate(10);
        return view('admin.users.index', compact('technicians'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => 2 // Teknisyen role_id'si
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Teknisyen başarıyla eklendi.');
    }

    public function edit(User $user)
    {
        if ($user->role_id != 2) {
            abort(404);
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role_id != 2) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Teknisyen başarıyla güncellendi.');
    }
} 