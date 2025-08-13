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
            'id_comunidade.required' => 'A comunidade é obrigatória.',
            'id_comunidade.exists' => 'A comunidade selecionada não existe.',
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
            'id_comunidade' => 'required|exists:comunidades,id',
        ], self::mensagens());
    }

    // Regras para atualização do cidadão
    public static function validarAtualizacao($data, $cidadao)
    {
        return validator($data, [
            'nome' => 'required|string|max:60',
            'email' => 'required|email|unique:cidadaos,email,' . $cidadao->id,
            'cpf' => 'required|string|cpf|unique:cidadaos,cpf,' . $cidadao->id,
            'telefone' => 'nullable|string|max:15|unique:cidadaos,telefone,' . $cidadao->id,
            'id_comunidade' => 'required|exists:comunidades,id',
            'email_verificado' => 'boolean',
            'ultimo_codigo' => 'nullable|string',
            'codigo_enviado_em' => 'nullable|date',
            'bloqueado' => 'boolean',
            'bloqueado_por' => 'nullable|string',
            'bloqueado_em' => 'nullable|date',
            'criado_em' => 'required|date',
        ], self::mensagens());
    }
}
