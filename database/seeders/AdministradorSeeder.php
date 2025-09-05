<?php

namespace Database\Seeders;

use App\Models\Administrador;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $administrador = [
            'nome' => 'Admin DEV',
            'email' => 'admin@dev.com',
            'senha' => '123456',
            'telefone' => '123456789',
        ];

        Administrador::create($administrador);
    }
}
