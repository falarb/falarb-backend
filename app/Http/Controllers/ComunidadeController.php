<?php

namespace App\Http\Controllers;

use App\Models\Comunidade;
use Illuminate\Http\Request;

class ComunidadeController extends Controller
{
    public function listar()
    {
        $limite = request()->query('limite', null);
        $pagina = (int) request()->query('pagina', 1);
        $ordenar_por = request()->query('ordenar_por', 'id');
        $ordenar_direcao = strtolower(request()->query('ordenar_direcao', 'asc'));
        $offset = $limite ? ($pagina - 1) * (int) $limite : 0;

        $query = Comunidade::query();

        // Filtros
        $nome = request()->query('nome', null);
        $ativo = request()->query('ativo', null);
        $termo_geral = request()->query('termo_geral', null);

        if ($nome) {
            $query->where('nome', 'like', "%$nome%");
        }
        if (!is_null($ativo)) {
            $query->where('ativo', filter_var($ativo, FILTER_VALIDATE_BOOLEAN));
        }
        if ($termo_geral) {
            $query->where('nome', 'like', "%$termo_geral%");
        }

        $query->withCount([
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
        ]);

        $total = $query->count();

        if ($limite === null || (int) $limite === 0) {
            // Sem paginação, retorna todos
            $comunidades = $query->orderBy($ordenar_por, $ordenar_direcao)->get();
        } else {
            $comunidades = $query->offset($offset)
                ->limit((int) $limite)
                ->orderBy($ordenar_por, $ordenar_direcao)
                ->get();
        }

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

        return respostaListagens($comunidades, $total, $limite, $pagina);
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
        $comunidade->update(['ativo' => false]);
        return response()->json(["message" => "Comunidade excluída com sucesso"], 200);
    }
}
