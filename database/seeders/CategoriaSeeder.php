<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;


class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [];

        for ($i = 1; $i <= 23; $i++) {
            $categorias[] = ['nome' => 'Categoria ' . $i];
        }

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}