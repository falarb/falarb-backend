<?php

namespace App\Http\Controllers;

use App\Http\Validation\SolicitacaoValidation;
use App\Mail\confirmaSolicitacao;
use App\Mail\solicitacaoAtualizada;
use App\Models\Cidadao;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Mail;
use ConsoleTVs\Profanity\Builder;

class SolicitacaoController extends Controller
{
    private function geraTokenSolicitacao()
    {
        do {
            $letras = '';
            for ($i = 0; $i < 3; $i++) {
                $letras .= chr(rand(65, 90)); // ASCII 65-90 = A-Z
            }

            $numeros = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

            $token = $letras . $numeros;

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

        // Filtros
        $status = request()->query('status', null);
        $categoria = request()->query('categoria', null);
        $comunidade = request()->query('comunidade', null);
        $cidadao = request()->query('cidadao', null);
        $termo_geral = request()->query('termo_geral', null);

        $query = Solicitacao::with(['cidadao', 'comunidade', 'categoria']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($categoria) {
            $query->where('id_categoria', $categoria);
        }

        if ($comunidade) {
            $query->where('id_comunidade', $comunidade);
        }

        if ($cidadao) {
            $query->where('id_cidadao', $cidadao);
        }

        if ($termo_geral) {
            $query->where(function ($q) use ($termo_geral) {
                $q->where('descricao', 'like', "%$termo_geral%")
                    ->orWhereHas('cidadao', function ($q) use ($termo_geral) {
                        $q->where('nome', 'like', "%$termo_geral%");
                    })
                    ->orWhereHas('comunidade', function ($q) use ($termo_geral) {
                        $q->where('nome', 'like', "%$termo_geral%");
                    })
                    ->orWhereHas('categoria', function ($q) use ($termo_geral) {
                        $q->where('nome', 'like', "%$termo_geral%");
                    });
            });
        }

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

        // Validação de palavras ofensivas
        $descricao = $request->input('descricao');
        if ($descricao && trim($descricao) !== '') {
            $profanityChecker = Builder::blocker($descricao);
            if (!$profanityChecker->clean()) {
                return response()->json([
                    'erro' => "Conteúdo ofensivo detectado na descrição. Por favor, revise o texto e tente novamente.",
                    'tipo' => 'texto ofensivo'
                ], 422);
            }
        }


        $cidadao_id = $request->input('id_cidadao');
        $cidadao = Cidadao::findOrFail($cidadao_id);

        // Gera o token único da solicitação
        $dadosSolicitacao = $request->all();
        $dadosSolicitacao['token_solicitacao'] = $this->geraTokenSolicitacao();
        $dadosSolicitacao['status'] = 'analise';

        $solicitacao = Solicitacao::create($dadosSolicitacao);

        // Envia o email de confirmação
        Mail::to($cidadao->email)->send(new confirmaSolicitacao(
            $solicitacao->token_solicitacao,
            $cidadao->nome
        ));

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

        if ($request->input('status')) {
            $solicitacao->load(['cidadao', 'categoria']);

            Mail::to($solicitacao->cidadao->email)->send(new solicitacaoAtualizada(
                $solicitacao->cidadao->nome,
                $solicitacao->categoria->nome,
                $solicitacao->created_at->format('d/m/Y H:i:s'),
                parseStatus($solicitacao->status),
                $solicitacao->token_solicitacao
            ));
        }

        return response()->json($solicitacao, 200);
    }
}
