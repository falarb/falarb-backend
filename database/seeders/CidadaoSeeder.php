<?php

namespace Database\Seeders;

use App\Models\Cidadao;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class CidadaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create('pt_BR');
        for ($i = 1; $i <= 782; $i++) {
            Cidadao::create([
                'nome' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'cpf' => $faker->cpf(false),
                'telefone' => $faker->cellphoneNumber,
                'ultimo_codigo' => $faker->numerify('####'),
            ]);
        }
    }
}