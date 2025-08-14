<?php

namespace App\Http\Controllers;

use App\Models\Comunidade;
use Illuminate\Http\Request;

class ComunidadeController extends Controller
{
    public function listar()
    {
        if (request()->query('comTotalSolicitacoes')) {
            $comunidades = Comunidade::withCount([
                'solicitacoes as total_solicitacoes',
                'solicitacoes as concluidas_solicitacoes' => function ($q) {
                    $q->where('status', 'concluida');
                },
                'solicitacoes as agendadas_solicitacoes' => function ($q) {
                    $q->where('status', 'agendada');
                },
                'solicitacoes as em_espera_solicitacoes' => function ($q) {
                    $q->where('status', 'analise');
                },
            ])->get();

            // Formata resposta agrupando os counts
            $comunidades->transform(function ($comunidade) {
                $comunidade->solicitacoes_info = [
                    'total' => $comunidade->total_solicitacoes,
                    'concluidas' => $comunidade->concluidas_solicitacoes,
                    'agendadas' => $comunidade->agendadas_solicitacoes,
                    'em_espera' => $comunidade->em_espera_solicitacoes,
                ];

                unset($comunidade->total_solicitacoes, $comunidade->concluidas_solicitacoes, $comunidade->agendadas_solicitacoes, $comunidade->em_espera_solicitacoes);
                return $comunidade;
            });
        } else {
            $comunidades = Comunidade::all();
        }
        return response()->json($comunidades, 200);
    }

    public function visualizar($id)
    {
        $comunidade = Comunidade::withCount([
            'solicitacoes as total_solicitacoes',
            'solicitacoes as concluidas_solicitacoes' => function ($q) {
                $q->where('status', 'concluida');
            },
            'solicitacoes as agendadas_solicitacoes' => function ($q) {
                $q->where('status', 'agendada');
            },
            'solicitacoes as em_espera_solicitacoes' => function ($q) {
                $q->where('status', 'analise');
            },
        ])->findOrFail($id);

        // Formata resposta agrupando os counts
        $comunidade->solicitacoes_info = [
            'total' => $comunidade->total_solicitacoes,
            'concluidas' => $comunidade->concluidas_solicitacoes,
            'agendadas' => $comunidade->agendadas_solicitacoes,
            'em_espera' => $comunidade->em_espera_solicitacoes,
        ];

        unset($comunidade->total_solicitacoes, $comunidade->concluidas_solicitacoes, $comunidade->agendadas_solicitacoes, $comunidade->em_espera_solicitacoes);

        return response()->json($comunidade, 200);
    }

    public function criar(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $comunidade = Comunidade::create($data);
        return response()->json($comunidade, 201);
    }

    public function atualizar(Request $request, $id)
    {
        $comunidade = Comunidade::findOrFail($id);
        $data = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
        ]);

        $comunidade->update($data);
        return response()->json($comunidade, 200);
    }

    public function excluir($id)
    {
        $comunidade = Comunidade::findOrFail($id);
        $comunidade->delete();
        return response()->json(["message" => "Comunidade excluída com sucesso"], 200);
    }
}
