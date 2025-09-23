<?php

namespace Database\Seeders;

use App\Models\Solicitacao;
use App\Models\Cidadao;
use App\Models\Categoria;
use App\Models\Comunidade;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class SolicitacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create('pt_BR');
        $cidadaos = Cidadao::pluck('id')->toArray();
        $categorias = Categoria::pluck('id')->toArray();
        $comunidades = Comunidade::pluck('id')->toArray();
        $statusList = ['analise', 'agendada', 'concluida', 'indeferida'];

        for ($i = 1; $i <= 1224; $i++) {
            $status = $faker->randomElement($statusList);
            $dataAgendamento = $faker->optional()->dateTimeBetween('-30 days', '+30 days');
            $dataConclusao = $status === 'concluida' ? $faker->dateTimeBetween('-30 days', 'now') : null;
            $motIndeferimento = $status === 'indeferida' ? $faker->sentence(12) : null;

            Solicitacao::create([
                'id' => $faker->regexify('[A-Za-z0-9]{24}'),
                'status' => $status,
                'mot_indeferimento' => $motIndeferimento,
                'descricao' => $faker->sentence(12),
                'data_agendamento' => $dataAgendamento ? $dataAgendamento->format('Y-m-d') : null,
                'data_conclusao' => $dataConclusao ? $dataConclusao->format('Y-m-d H:i:s') : null,
                'token_solicitacao' => strtoupper($faker->lexify('???')) . $faker->numerify('###'),
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'id_cidadao' => $faker->randomElement($cidadaos),
                'id_categoria' => $faker->randomElement($categorias),
                'id_comunidade' => $faker->randomElement($comunidades),
            ]);
        }
    }
}
