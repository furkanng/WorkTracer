<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin kullanıcısı
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);

        // Teknisyen kullanıcısı
        User::create([
            'name' => 'Teknisyen User',
            'email' => 'teknisyen@teknisyen.com',
            'password' => Hash::make('123456'),
            'role_id' => Role::where('name', 'technician')->first()->id
        ]);

        // Sekreter kullanıcısı
        User::create([
            'name' => 'Sekreter User',
            'email' => 'sekreter@sekreter.com',
            'password' => Hash::make('123456'),
            'role_id' => Role::where('name', 'secretary')->first()->id
        ]);
    }
} 