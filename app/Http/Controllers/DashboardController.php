<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Solicitacao;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indicadores()
    {
        $id_usuario = request()->query('id_usuario');
        $id_comunidade = request()->query('id_comunidade');

        $query = Solicitacao::query();
        if ($id_usuario) {
            $query->where('id_cidadao', $id_usuario);
        }

        if ($id_comunidade) {
            $query->where('id_comunidade', $id_comunidade);
        }

        // Solicitações por status
        $solicitacoesPorStatus = [
            'total' => $query->count(),
            'analise' => $query->where('status', 'analise')->count(),
            'agendada' => $query->where('status', 'agendada')->count(),
            'concluida' => $query->where('status', 'concluida')->count(),
            'indeferida' => $query->where('status', 'indeferida')->count(),
        ];

        // 6 Categorias de solicitações mais requisitadas
        $categoriasIds = $query->select('id_categoria')
            ->groupBy('id_categoria')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(6)
            ->pluck('id_categoria');

        $solicitacoesPorCategoria = Categoria::whereIn('id', $categoriasIds)
            ->withCount(['solicitacoes AS total_solicitacoes'])
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
