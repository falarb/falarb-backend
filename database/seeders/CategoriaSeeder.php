<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;


class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nome' => 'Pedido de cascalhamento'],
            ['nome' => 'Pedido de abertura de estrada'],
            ['nome' => 'Pedido de terraplanagem'],
            ['nome' => 'Pedido de serviço de máquina (retro escavadeira ou trator agrícola)'],
            ['nome' => 'Pedido de manutenção de estrada'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}