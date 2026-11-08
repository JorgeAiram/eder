<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@proveedores.com'],
            [
                'name' => 'Administrador del Sistema',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'operador@proveedores.com'],
            [
                'name' => 'Operador de Compras',
                'password' => Hash::make('operador123'),
                'role' => 'usuario',
            ]
        );
    }
}
