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
        $cidadaos = Cidadao::all();

        // Total de solicitações feitas pelo usuário
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
        $validatedData = CidadaoValidation::validarCriacao($request->all());
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 422);
        }

        $cidadao = Cidadao::create($validatedData->validated());

        return response()->json([
            "id" => $cidadao->id,
            "nome" => $cidadao->nome,
            "email" => $cidadao->email,
        ], 201);
    }

    public function atualizar(Request $request, $id)
    {
        $cidadao = Cidadao::findOrFail($id);

        $validatedData = CidadaoValidation::validarAtualizacao($request->all(), $cidadao);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 422);
        }

        $cidadao->update($validatedData->validated());

        return response()->json($cidadao, 200);
    }

    public function visualizar($id)
    {
        $cidadao = Cidadao::findOrFail($id);

        return response()->json($cidadao, 200);
    }

    public function enviaToken($id)
    {
        $cidadao = Cidadao::findOrFail($id);

        if ($cidadao->bloqueado) {
            return response()->json(['message' => 'Cidadão bloqueado'], 403);
        }

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

        $cidadao = Cidadao::where('email', $email)->first();

        if ($cidadao) {
            return response()->json(['id' => $cidadao->id], 200);
        }

        return response()->json(['id' => null], 200);
    }
}
