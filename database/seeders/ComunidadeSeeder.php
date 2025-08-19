<?php

namespace Database\Seeders;

use App\Models\Comunidade;
use Illuminate\Database\Seeder;


class ComunidadeSeeder extends Seeder
{
    public function run(): void
    {
        $comunidades = [
            ['nome' => 'Comunidade 1'],
            ['nome' => 'Comunidade 2'],
            ['nome' => 'Comunidade 3'],
            ['nome' => 'Comunidade 4'],
            ['nome' => 'Comunidade 5'],
        ];

        foreach ($comunidades as $comunidade) {
            Comunidade::create($comunidade);
        }
    }
}