<?php

namespace App\Http\Controllers;

use App\Http\Validation\CidadaoValidation;
use App\Mail\tokenValidaEmail;
use App\Models\Cidadao;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Mail;

class CidadaoController extends Controller
{

    public function listar()
    {
        $cidadaos = Cidadao::with(['comunidade'])->get();

        // Para devolver a listagem com o total de solicitações feitas pelo usuário
        if (request()->query('comTotalSolicitacoes')) {
            foreach ($cidadaos as $cidadao) {
                $totalSolicitacoes = Solicitacao::where('id_cidadao', $cidadao->id)
                    ->count();
                $cidadao->total_solicitacoes = $totalSolicitacoes;
            }
        }

        return response()->json($cidadaos, 200);
    }

    public function criar(Request $request)
    {
        // Validação dos dados do cidadão
        $validatedData = CidadaoValidation::validarCriacao($request->all());

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 422);
        }

        // Criação do cidadão
        $cidadao = Cidadao::create($validatedData->validated());

        return response()->json([
            "id" => $cidadao->id,
            "nome" => $cidadao->nome,
            "email" => $cidadao->email,
        ], 201);
    }

    public function atualizar(Request $request, $id)
    {
        // Busca o cidadão pelo ID
        $cidadao = Cidadao::findOrFail($id);

        // Validação dos dados do cidadão
        $validatedData = CidadaoValidation::validarAtualizacao($request->all(), $cidadao);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 422);
        }

        // Atualiza os dados do cidadão
        $cidadao->update($validatedData->validated());

        return response()->json($cidadao, 200);
    }

    public function visualizar($id)
    {
        // Busca o cidadão pelo ID
        $cidadao = Cidadao::findOrFail($id);

        return response()->json($cidadao, 200);
    }

    public function enviaToken($id)
    {
        $cidadao = Cidadao::findOrFail($id);

        // Verifica se o cidadão está bloqueado
        if ($cidadao->bloqueado) {
            return response()->json(['message' => 'Cidadão bloqueado'], 403);
        }

        // Lógica para enviar o token de validação de e-mail
        $token = geraToken();
        $cidadao->ultimo_codigo = $token;
        $cidadao->codigo_enviado_em = now();
        $cidadao->save();

        Mail::to($cidadao->email)->send(new tokenValidaEmail($token));

        return response()->json(['message' => 'Token enviado com sucesso'], 200);
    }

    public function verificaEmail(Request $request, $id)
    {
        $token = $request->input('token');
        $cidadao = Cidadao::findOrFail($id);
        $tokenCorreto = $cidadao->ultimo_codigo;

        if (!$token) {
            return response()->json(['message' => 'Token é obrigatório'], 422);
        }

        if ($token !== $tokenCorreto) {
            return response()->json(['message' => 'Token inválido'], 400);
        }

        return response()->json(['message' => 'Verificação de e-mail realizada com sucesso'], 200);
    }

    public function emailExiste(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return response()->json(['message' => 'Parâmetro email é obrigatório'], 422);
        }

        $existe = Cidadao::where('email', $email)->exists();

        return response()->json(['existe' => $existe], 200);
    }
}
