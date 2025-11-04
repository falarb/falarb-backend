<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function listar()
    {
        $limite = request()->query('limite', null);
        $pagina = (int) request()->query('pagina', 1);
        $ordenar_por = request()->query('ordenar_por', 'id');
        $ordenar_direcao = strtolower(request()->query('ordenar_direcao', 'asc'));
        $offset = $limite ? ($pagina - 1) * (int) $limite : 0;

        // Filtros
        $ativo = request()->query('ativo', null);
        $termo_geral = request()->query('termo_geral', null);

        $query = Categoria::query();

        if (!is_null($ativo)) {
            $query->where('ativo', filter_var($ativo, FILTER_VALIDATE_BOOLEAN));
        }

        if (!is_null($termo_geral)) {
            $query->where(function ($q) use ($termo_geral) {
                $q->where('nome', 'like', '%' . $termo_geral . '%');
            });
        }

        $total = $query->count();

        if ($limite === null || (int) $limite === 0) {
            // Sem paginação, retorna todos
            $categorias = $query->orderBy($ordenar_por, $ordenar_direcao)->get();
        } else {
            $categorias = $query->offset($offset)
                ->limit((int) $limite)
                ->orderBy($ordenar_por, $ordenar_direcao)
                ->get();
        }

        return respostaListagens($categorias, $total, $limite, $pagina);
    }

    public function criar(Request $request)
    {
        $dadosCategoria = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $categoria = Categoria::create($dadosCategoria);
        return response()->json($categoria, 201);
    }

    public function visualizar($id)
    {
        $categoria = Categoria::findOrFail($id);
        return response()->json($categoria, 200);
    }

    public function atualizar(Request $request, $id)
    {
        $dadosCategoria = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'ativo' => 'sometimes|boolean',
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update($dadosCategoria);

        return response()->json($categoria, 200);
    }

    public function excluir($id)
    {
        $categoria = Categoria::findOrFail($id);

        $temSolicitacoes = $categoria->solicitacoes()->exists();
        if ($temSolicitacoes) {
            return response()->json(['message' => 'Não é possível excluir a categoria pois existem solicitações associadas a ela.'], 400);
        }

        $categoria->update(['ativo' => false]);
        return response()->json(['message' => 'Categoria excluída com sucesso'], 200);
    }
}
