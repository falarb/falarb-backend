<?php

namespace App\Http\Validation;

class SolicitacaoValidation
{
    public static function messages()
    {
        return [
            'id_cidadao.required' => 'O campo id_cidadao é obrigatório.',
            'id_cidadao.exists' => 'O cidadão informado não existe.',
            'id_categoria.required' => 'O campo id_categoria é obrigatório.',
            'id_categoria.exists' => 'A categoria informada não existe.',
            'id_comunidade.required' => 'O campo id_comunidade é obrigatório.',
            'id_comunidade.exists' => 'A comunidade informada não existe.',
            'status.in' => 'O status deve ser um dos tipos: :values',
            'data_agendamento.date' => 'A data de agendamento deve ser uma data válida.',
            'data_conclusao.date' => 'A data de conclusão deve ser uma data válida.',
            'latitude.required' => 'A latitude é obrigatória.',
            'latitude.numeric' => 'A latitude deve ser um número.',
            'longitude.required' => 'A longitude é obrigatória.',
            'longitude.numeric' => 'A longitude deve ser um número.',
        ];
    }

    public static function validarCriacao(array $data)
    {
        return validator($data, [
            'id_cidadao' => 'required|exists:cidadaos,id',
            'id_categoria' => 'required|exists:categorias,id',
            'id_comunidade' => 'required|exists:comunidades,id',
            'descricao' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], self::messages());
    }

    public static function validarAtualizacao(array $data, $solicitacao)
    {
        return validator($data, [
            'id_cidadao' => 'sometimes|required|exists:cidadaos,id',
            'id_categoria' => 'sometimes|required|exists:categorias,id',
            'id_comunidade' => 'sometimes|required|exists:comunidades,id',
            'status' => 'sometimes|in:analise,agendada,concluida,indeferida',
            'data_agendamento' => 'sometimes|nullable|date',
            'data_conclusao' => 'sometimes|nullable|date',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
        ], self::messages());
    }
}