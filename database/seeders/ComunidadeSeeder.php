<?php

namespace Database\Seeders;

use App\Models\Comunidade;
use Illuminate\Database\Seeder;


class ComunidadeSeeder extends Seeder
{
    public function run(): void
    {
        $comunidades = [
            ['nome' => 'Rio Corrente'],
            ['nome' => 'Cachoeira dos Domingues'],
            ['nome' => 'Água Quente dos Luz'],
            ['nome' => 'Passo do Potinga'],
            ['nome' => 'Potinga'],
            ['nome' => 'Serra dos Franco'],
            ['nome' => 'Butiazal'],
            ['nome' => 'Colônia Cachoeira'],
            ['nome' => 'Sunira'],
            ['nome' => 'Paredão'],
            ['nome' => 'Bugio'],
            ['nome' => 'Barreiro'],
            ['nome' => 'Riozinho de Baixo'],
            ['nome' => 'Riozinho dos Santos'],
            ['nome' => 'Rodeio'],
            ['nome' => 'Poço Bonito'],
            ['nome' => 'Faxinal dos Vieiras'],
            ['nome' => 'Faxinal dos Franco'],
            ['nome' => 'Salto'],
            ['nome' => 'Marmeleiro dos Ingleses'],
            ['nome' => 'Marmeleiro de Cima'],
            ['nome' => 'Faxinal dos Lapeanos'],
            ['nome' => 'Marmeleiro dos Rosa'],
            ['nome' => 'Conceição'],
            ['nome' => 'Palmeiral'],
            ['nome' => 'Saltinho'],
            ['nome' => 'Barreirinho'],
            ['nome' => 'Cochos'],
            ['nome' => 'Rio Bonito'],
            ['nome' => 'Pântano'],
            ['nome' => 'Pântano Preto'],
            ['nome' => 'Faxinal Marmeleiro'],
            ['nome' => 'Barro Branco'],
            ['nome' => 'Turvo'],
            ['nome' => 'Centro'],
        ];

        foreach ($comunidades as $comunidade) {
            Comunidade::create($comunidade);
        }
    }
}