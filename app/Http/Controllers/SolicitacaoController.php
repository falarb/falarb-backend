<?php

namespace App\Http\Controllers;

use App\Http\Validation\SolicitacaoValidation;
use App\Mail\confirmaSolicitacao;
use App\Models\Cidadao;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Mail;

class SolicitacaoController extends Controller
{
    private function geraTokenSolicitacao()
    {
        do {
            $letras = '';
            for ($i = 0; $i < 3; $i++) {
                $letras .= chr(rand(65, 90)); // ASCII 65-90 = A-Z
            }

            // Gera 3 números aleatórios (000-999)
            $numeros = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

            $token = $letras . $numeros;

            // Verifica se o token já existe na base de dados
            $tokenExiste = Solicitacao::where('token_solicitacao', $token)->exists();

        } while ($tokenExiste);

        return $token;
    }

    public function listar()
    {
        $limite = (int) request()->query('limite', 10);
        $pagina = (int) request()->query('pagina', 1);
        $ordenar_por = request()->query('ordenar_por', 'id');
        $ordenar_direcao = strtolower(request()->query('ordenar_direcao', 'asc'));
        $offset = ($pagina - 1) * $limite;

        $query = Solicitacao::with(['cidadao', 'comunidade', 'categoria']);
        $total = $query->count();

        $solicitacoes = $query->offset($offset)
            ->limit($limite)
            ->orderBy($ordenar_por, $ordenar_direcao)
            ->get();

        return respostaListagens($solicitacoes, $total, $limite, $pagina);
    }

    public function visualizar($id)
    {
        $solicitacao = Solicitacao::with(['cidadao', 'comunidade', 'categoria'])
            ->findOrFail($id);

        return response()->json($solicitacao, 200);
    }

    public function criar(Request $request)
    {
        // Verifica se existe 5 solicitações abertas para o cidadão
        $solicitacoesAbertas = Solicitacao::where('id_cidadao', $request->input('id_cidadao'))
            ->where('status', 'analise')
            ->orWhere('status', 'agendada')
            ->count();

        if ($solicitacoesAbertas >= 5) {
            return response()->json(['error' => 'Limite de 5 solicitações abertas atingido.'], 400);
        }

        $validator = SolicitacaoValidation::validarCriacao($request->all());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cidadao_id = $request->input('id_cidadao');
        $cidadao = Cidadao::findOrFail($cidadao_id);

        // Gera o token único da solicitação
        $dadosSolicitacao = $request->all();
        $dadosSolicitacao['token_solicitacao'] = $this->geraTokenSolicitacao();
        $dadosSolicitacao['status'] = 'analise';

        // Lógica para criar a solicitação
        $solicitacao = Solicitacao::create($dadosSolicitacao);

        // Envia o email de confirmação
        Mail::to($cidadao->email)->send(new confirmaSolicitacao($solicitacao->token_solicitacao));

        return response()->json($solicitacao, 201);
    }

    public function buscaPorToken($token)
    {
        $solicitacao = Solicitacao::where('token_solicitacao', $token)
            ->with(['cidadao', 'comunidade', 'categoria'])
            ->first();

        if (!$solicitacao) {
            return response()->json(['error' => 'Solicitação não encontrada.'], 404);
        }

        return response()->json($solicitacao, 200);
    }

    public function atualizar(Request $request, $id)
    {
        $solicitacao = Solicitacao::findOrFail($id);

        $validator = SolicitacaoValidation::validarAtualizacao($request->all(), $solicitacao);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $request->user();

        $solicitacao->update(array_merge($request->all(), [
            'atualizado_por' => $user->id,
        ]));

        return response()->json($solicitacao, 200);
    }

}
