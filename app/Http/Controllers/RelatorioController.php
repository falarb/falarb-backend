<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Spatie\Browsershot\Browsershot;

class RelatorioController extends Controller
{
    public function geral()
    {
        // Filtro de Tipo de Solicitação
        $categoria = request()->input('categoria');

        // Comunidade
        $comunidade = request()->input('comunidade');

        // Por data (pré-definidos)
        $data = request()->input('data');

        $query = Solicitacao::query();

        if ($categoria && $categoria != 'todas') {
            $query->where('id_categoria', $categoria);
        }

        if ($comunidade && $comunidade != 'todas') {
            $query->where('id_comunidade', $comunidade);
        }

        $dataEscrita = 'Último ano';

        if ($data == 'ultima_semana') {
            $query->where('created_at', '>=', now()->subWeek());
            $dataEscrita = 'Última semana';
        } else if ($data == 'duas_semanas') {
            $query->where('created_at', '>=', now()->subWeeks(2));
            $dataEscrita = 'Últimas duas semanas';
        } else if ($data == 'ultimo_mes') {
            $query->where('created_at', '>=', now()->subMonth());
            $dataEscrita = 'Último mês';
        } else if ($data == 'ultimo_bimestre') {
            $query->where('created_at', '>=', now()->subMonths(2));
            $dataEscrita = 'Último bimestre';
        } else if ($data == 'ultimo_semestre') {
            $query->where('created_at', '>=', now()->subMonths(6));
            $dataEscrita = 'Último semestre';
        } else if ($data == 'ultimo_ano') {
            $query->where('created_at', '>=', now()->subYear());
            $dataEscrita = 'Último ano';
        }

        $dadosPdf = $query->with(['categoria', 'comunidade', 'cidadao'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($dadosPdf->isEmpty()) {
            return response()->json(['message' => 'Nenhum dado encontrado para os filtros aplicados.'], 404);
        }

        foreach ($dadosPdf as $dado) {
            $dado->status_cor = pegaCorStatus($dado->status);
            $dado->status = ucfirst($dado->status);
        }

        $pdf = Browsershot::html(view('relatorios.geral', [
            'dados' => $dadosPdf,
            'dataEscrita' => $dataEscrita,
            'categoria' => $categoria,
            'comunidade' => $comunidade,
        ])->render())
            ->format('A4')
            ->landscape()
            ->margins(0, 0, 0, 0)
            ->showBackground()
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="relatorio_geral.pdf"');
    }
}
