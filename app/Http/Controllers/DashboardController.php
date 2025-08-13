<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Solicitacao;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indicadores()
    {
        // Solicitações por status
        $solicitacoesPorStatus = [
            'analise' => Solicitacao::where('status', 'analise')->count(),
            'agendada' => Solicitacao::where('status', 'agendada')->count(),
            'concluida' => Solicitacao::where('status', 'concluida')->count(),
            'indeferida' => Solicitacao::where('status', 'indeferida')->count(),
            'total' => Solicitacao::count(),
        ];

        // 6 Categorias de solicitações mais requisitadas
        $categoriasIds = Solicitacao::select('id_categoria')
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
