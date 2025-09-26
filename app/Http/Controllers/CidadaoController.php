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
        $limite = (int) request()->query('limite', 10);
        $pagina = (int) request()->query('pagina', 1);
        $ordenar_por = request()->query('ordenar_por', 'id');
        $ordenar_direcao = strtolower(request()->query('ordenar_direcao', 'asc'));
        $offset = ($pagina - 1) * $limite;

        $query = Cidadao::query();

        // Filtros
        $nome = request()->query('nome', null);
        $email = request()->query('email', null);
        $cpf = request()->query('cpf', null);
        $ativo = request()->query('ativo', null);
        $termo_geral = request()->query('termo_geral', null);

        if ($nome) {
            $query->where('nome', 'like', "%$nome%");
        }
        if ($email) {
            $query->where('email', 'like', "%$email%");
        }
        if ($cpf) {
            $query->where('cpf', 'like', "%$cpf%");
        }
        if (!is_null($ativo)) {
            $query->where('ativo', filter_var($ativo, FILTER_VALIDATE_BOOLEAN));
        }
        if ($termo_geral) {
            $query->where(function ($q) use ($termo_geral) {
                $q->where('nome', 'like', "%$termo_geral%")
                    ->orWhere('email', 'like', "%$termo_geral%")
                    ->orWhere('cpf', 'like', "%$termo_geral%");
            });
        }

        $total = $query->count();

        $cidadaos = $query->offset($offset)
            ->limit($limite)
            ->orderBy($ordenar_por, $ordenar_direcao)
            ->get();

        if (request()->query('comTotalSolicitacoes')) {
            $cidadaoIds = $cidadaos->pluck('id');
            $totalSolicitacoesPorCidadao = Solicitacao::whereIn('id_cidadao', $cidadaoIds)
                ->selectRaw('id_cidadao, count(*) as total')
                ->groupBy('id_cidadao')
                ->pluck('total', 'id_cidadao');

            foreach ($cidadaos as $cidadao) {
                $cidadao->total_solicitacoes = $totalSolicitacoesPorCidadao->get($cidadao->id, 0);
            }
        }

        return respostaListagens($cidadaos, $total, $limite, $pagina);
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

        $cidadao->total_solicitacoes = Solicitacao::where('id_cidadao', $cidadao->id)->count();

        return response()->json($cidadao, 200);
    }

    public function enviaToken($id, Request $request)
    {
        $cidadao = Cidadao::findOrFail($id);
        $reenviarCodigo = $request->input('reenviar_codigo', false);

        if ($cidadao->bloqueado) {
            return response()->json(['message' => 'Cidadão bloqueado'], 403);
        }

        $token = $cidadao->ultimo_codigo;

        if (!$reenviarCodigo || !$token) {
            $token = geraToken();
            $cidadao->ultimo_codigo = $token;
            $cidadao->codigo_enviado_em = now();
            $cidadao->save();
        }

        // Mail::to($cidadao->email)->send(new tokenValidaEmail($token, $cidadao->nome));

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

        if (!$cidadao) {
            return response()->json(['id' => null], 200);
        }

        $solicitacoesAbertas = solicitacoesAbertas($cidadao->id);
        if ($solicitacoesAbertas >= 5) {
            return response()->json([
                'error' => 'max_solicitacoes_abertas',
                'mensagem' => 'Você já possui 5 solicitações em análise ou agendadas. Por favor, aguarde a conclusão de alguma delas antes de abrir uma nova solicitação.'
            ], 400);
        } else if ($cidadao->bloqueado) {
            return response()->json([
                'error' => 'cidadao_bloqueado',
                'mensagem' => 'Seu acesso ao sistema está bloqueado. Por favor, entre em contato com o suporte para mais informações.'
            ], 403);
        } else if (!$cidadao->ativo) {
            return response()->json([
                'error' => 'cidadao_inativo',
                'mensagem' => 'Seu cadastro está inativo. Por favor, entre em contato com o suporte para mais informações.'
            ], 403);
        }

        return response()->json(['id' => $cidadao->id], 200);
    }

    public function atualizaEmail(Request $request, $id)
    {
        $cidadao = Cidadao::findOrFail($id);
        $novoEmail = trim($request->input('email'));

        if (!$novoEmail) {
            return response()->json(['message' => 'O campo email é obrigatório'], 422);
        }

        if ($cidadao->email === $novoEmail) {
            return response()->json(['message' => 'O novo email deve ser diferente do email atual'], 422);
        }

        $emailExistente = Cidadao::where('email', $novoEmail)->first();
        if ($emailExistente) {
            return response()->json(['message' => 'O email informado já está em uso por outro cidadão'], 409);
        }

        $cidadao->email = $novoEmail;
        $cidadao->save();

        return response()->json(['message' => 'Email atualizado com sucesso', 'email' => $cidadao->email], 200);
    }

    public function excluir($id)
    {
        $cidadao = Cidadao::findOrFail($id);
        $cidadao->update(['ativo' => false]);
        return response()->json(["message" => "Cidadão excluído com sucesso"], 200);
    }
}
