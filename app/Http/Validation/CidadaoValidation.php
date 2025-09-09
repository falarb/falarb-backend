<?php

namespace App\Http\Validation;

class CidadaoValidation
{
    // Mensagens de erro personalizadas
    public static function mensagens()
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.cpf' => 'O CPF deve ser válido.',
            'cpf.unique' => 'Este CPF já está em uso.',
            'telefone.unique' => 'Este telefone já está em uso.',
        ];
    }

    // Regras para criação do cidadão
    public static function validarCriacao($data)
    {
        return validator($data, [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:cidadaos,email',
            'cpf' => 'required|string|max:11|unique:cidadaos,cpf',
            'telefone' => 'nullable|string|max:15|unique:cidadaos,telefone',
        ], self::mensagens());
    }

    // Regras para atualização do cidadão
    public static function validarAtualizacao($data, $cidadao)
    {
        return validator($data, [
            'nome' => 'sometimes|required|string|max:60',
            'email' => 'sometimes|required|email|unique:cidadaos,email,' . $cidadao->id,
            'cpf' => 'sometimes|required|string|unique:cidadaos,cpf,' . $cidadao->id,
            'telefone' => 'sometimes|nullable|string|max:15|unique:cidadaos,telefone,' . $cidadao->id,
            'email_verificado' => 'sometimes|boolean',
            'ultimo_codigo' => 'sometimes|nullable|string',
            'codigo_enviado_em' => 'sometimes|nullable|date',
            'bloqueado' => 'sometimes|boolean',
            'bloqueado_por' => 'sometimes|nullable|string',
            'bloqueado_em' => 'sometimes|nullable|date',
        ], self::mensagens());
    }
}
