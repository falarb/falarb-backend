<?php

use App\Models\Solicitacao;

// Gera um token de 4 dígitos
function geraToken()
{
    $token = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

    return $token;
}

// Pega a cor pelo status da solicitação
function pegaCorStatus($status)
{
    switch ($status) {
        case 'analise':
            return '#f59e0b';
        case 'concluida':
            return '#22c55e';
        case 'agendada':
            return '#f59e0b';
        case 'indeferida':
            return '#ef4444';
        default:
            return '#9ca3af';
    }
}

function parseStatus($status)
{
    switch (strtolower($status)) {
        case 'analise':
            return 'Em Análise';
        case 'concluida':
            return 'Concluída';
        case 'agendada':
            return 'Agendada';
        case 'indeferida':
            return 'Indeferida';
        default:
            return 'Desconhecido';
    }
}

// Componente padrão para respostas de listagens com paginação
function respostaListagens($dados, $total, $limite, $pagina)
{
    if (!$limite) {
        $limite = $total ?: 1;
        $pagina = 1;
    }
    $totalPaginas = (int) ceil($total / $limite);
    $offset = ($pagina - 1) * $limite;
    $temProximaPagina = $pagina < $totalPaginas;
    $temPaginaAnterior = $pagina > 1;

    return response()->json([
        'dados' => $dados,
        'total' => $total,
        'por_pagina' => $limite,
        'pagina_atual' => $pagina,
        'ultima_pagina' => $totalPaginas,
        'de' => $total > 0 ? $offset + 1 : 0,
        'ate' => $total > 0 ? min($offset + $limite, $total) : 0,
        'tem_proxima_pagina' => $temProximaPagina,
        'tem_pagina_anterior' => $temPaginaAnterior,
    ], 200);
}

function solicitacoesAbertas($id_cidadao)
{
    return Solicitacao::where('id_cidadao', $id_cidadao)
        ->where(function ($query) {
            $query->where('status', 'analise')
                ->orWhere('status', 'agendada');
        })
        ->count();
}