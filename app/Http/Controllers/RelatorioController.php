<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $dataEscrita = 'Último ano'; // valor padrão

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

        // Gerar PDF usando a Facade correta
        $pdf = Pdf::loadView('relatorios.geral', [
            'dados' => $dadosPdf,
            'dataEscrita' => $dataEscrita,
            'categoria' => $categoria,
            'comunidade' => $comunidade,
        ]);
        
        $pdf->setPaper('A4', 'landscape');

        // Retornar o PDF
        return $pdf->stream('relatorio_geral.pdf');
    }
}
