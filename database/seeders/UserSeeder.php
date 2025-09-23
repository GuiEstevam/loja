<?php

// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@loja.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Clientes
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "cliente{$i}@gmail.com"],
                [
                    'name' => "Cliente {$i}",
                    'password' => Hash::make('cliente123'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('cliente');
        }
    }
}
