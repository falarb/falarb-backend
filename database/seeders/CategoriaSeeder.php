<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;


class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nome' => 'Categoria 1'],
            ['nome' => 'Categoria 2'],
            ['nome' => 'Categoria 3'],
            ['nome' => 'Categoria 4'],
            ['nome' => 'Categoria 5'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}