<?php

namespace Database\Seeders;

use App\Models\Comunidade;
use Illuminate\Database\Seeder;


class ComunidadeSeeder extends Seeder
{
    public function run(): void
    {
        $comunidades = [];

        for ($i = 1; $i <= 23; $i++) {
            $comunidades[] = ['nome' => 'Comunidade ' . $i];
        }

        foreach ($comunidades as $comunidade) {
            Comunidade::create($comunidade);
        }
    }
}