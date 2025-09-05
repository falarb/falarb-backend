<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Hash;
use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    public function listar()
    {
        $limite = (int) request()->query('limite', 10);
        $pagina = (int) request()->query('pagina', 1);
        $ordenar_por = request()->query('ordenar_por', 'id');
        $ordenar_direcao = strtolower(request()->query('ordenar_direcao', 'asc'));
        $offset = ($pagina - 1) * $limite;

        $query = Administrador::query();

        // Filtros
        $nome = request()->query('nome', null);
        $email = request()->query('email', null);
        $telefone = request()->query('telefone', null);
        $ativo = request()->query('ativo', null);
        $termo_geral = request()->query('termo_geral', null);

        if ($nome) {
            $query->where('nome', 'like', "%$nome%");
        }
        if ($email) {
            $query->where('email', 'like', "%$email%");
        }
        if ($telefone) {
            $query->where('telefone', 'like', "%$telefone%");
        }
        if (!is_null($ativo)) {
            $query->where('ativo', filter_var($ativo, FILTER_VALIDATE_BOOLEAN));
        }

        if ($termo_geral) {
            $query->where(function ($q) use ($termo_geral) {
                $q->where('nome', 'like', "%$termo_geral%")
                    ->orWhere('email', 'like', "%$termo_geral%")
                    ->orWhere('telefone', 'like', "%$termo_geral%");
            });
        }

        $total = $query->count();

        $administradores = $query->offset($offset)
            ->orderBy($ordenar_por, $ordenar_direcao)
            ->limit($limite)
            ->get();

        return respostaListagens($administradores, $total, $limite, $pagina);
    }

    public function criar(Request $request)
    {
        $administrador = Administrador::create($request->all());
        return response()->json($administrador, 201);
    }

    public function visualizar($id)
    {
        $administrador = Administrador::findOrFail($id);
        return response()->json($administrador, 200);
    }

    public function atualizar(Request $request, $id)
    {
        $administrador = Administrador::findOrFail($id);

        // Se o usuário está sendo desativado, limpa o token de autenticação
        if (isset($request->ativo) && !$request->ativo) {
            $administrador->tokens()->delete();
        }

        $administrador->update($request->all());
        return response()->json($administrador, 200);
    }
}
