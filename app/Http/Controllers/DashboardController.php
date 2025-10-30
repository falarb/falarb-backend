<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Solicitacao;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indicadores()
    {
        $id_cidadao = request()->query('id_cidadao');
        $id_comunidade = request()->query('comunidade_id');
        $data = request()->query('data');

        // Filtros iniciais
        $baseQuery = Solicitacao::query();
        if ($id_cidadao) {
            $baseQuery->where('id_cidadao', $id_cidadao);
        }
        if ($id_comunidade) {
            $baseQuery->where('id_comunidade', $id_comunidade);
        }

        if ($data == 'ultima_semana') {
            $baseQuery->where('created_at', '>=', now()->subWeek());
        } else if ($data == 'duas_semanas') {
            $baseQuery->where('created_at', '>=', now()->subWeeks(2));
        } else if ($data == 'ultimo_mes') {
            $baseQuery->where('created_at', '>=', now()->subMonth());
        } else if ($data == 'ultimo_bimestre') {
            $baseQuery->where('created_at', '>=', now()->subMonths(2));
        } else if ($data == 'ultimo_semestre') {
            $baseQuery->where('created_at', '>=', now()->subMonths(6));
        } else if ($data == 'ultimo_ano') {
            $baseQuery->where('created_at', '>=', now()->subYear());
        }

        // Solicitações por status (usando clone para não acumular where)
        $solicitacoesPorStatus = [
            'total' => (clone $baseQuery)->count(),
            'analise' => (clone $baseQuery)->where('status', 'analise')->count(),
            'agendada' => (clone $baseQuery)->where('status', 'agendada')->count(),
            'concluida' => (clone $baseQuery)->where('status', 'concluida')->count(),
            'indeferida' => (clone $baseQuery)->where('status', 'indeferida')->count(),
        ];

        // 6 Categorias de solicitações mais requisitadas
        $categoriasIds = (clone $baseQuery)
            ->select('id_categoria')
            ->groupBy('id_categoria')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(6)
            ->pluck('id_categoria');

        $solicitacoesPorCategoria = Categoria::whereIn('id', $categoriasIds)
            ->withCount(['solicitacoes AS total_solicitacoes' => function ($query) use ($id_cidadao, $id_comunidade, $data) {
                if ($id_cidadao) {
                    $query->where('id_cidadao', $id_cidadao);
                }
                if ($id_comunidade) {
                    $query->where('id_comunidade', $id_comunidade);
                }
                if ($data == 'ultima_semana') {
                    $query->where('created_at', '>=', now()->subWeek());
                } else if ($data == 'duas_semanas') {
                    $query->where('created_at', '>=', now()->subWeeks(2));
                } else if ($data == 'ultimo_mes') {
                    $query->where('created_at', '>=', now()->subMonth());
                } else if ($data == 'ultimo_bimestre') {
                    $query->where('created_at', '>=', now()->subMonths(2));
                } else if ($data == 'ultimo_semestre') {
                    $query->where('created_at', '>=', now()->subMonths(6));
                } else if ($data == 'ultimo_ano') {
                    $query->where('created_at', '>=', now()->subYear());
                }
            }])
            ->get()
            ->mapWithKeys(function ($categoria) {
                return [$categoria->nome => $categoria->total_solicitacoes];
            });

        return response()->json([
            'solicitacoes_por_status' => $solicitacoesPorStatus,
            'solicitacoes_por_categoria' => $solicitacoesPorCategoria,
        ], 200);
    }
}
