<?php

namespace Database\Seeders;

use App\Models\Cidadao;
use Hash;
use Illuminate\Database\Seeder;


class CidadaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Cidadao::create([
                'nome' => 'Cidadão ' . $i,
                'email' => 'cidadao' . $i . '@example.com',
                'cpf' => str_pad($i, 11, '0', STR_PAD_LEFT),
                'telefone' => '11999999' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'ultimo_codigo' => 'AB12',
                'codigo_enviado_em' => now(),
                'bloqueado' => false,
                'bloqueado_em' => null,
                'bloqueado_por' => null,
            ]);
        }
    }
}