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

        $query = Categoria::query();
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
        $user = $request->user();

        $dadosCategoria = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $categoria = Categoria::create(array_merge($dadosCategoria, ['criado_por' => $user->id]));
        return response()->json($categoria, 201);
    }

    public function visualizar($id)
    {
        $categoria = Categoria::findOrFail($id);
        return response()->json($categoria, 200);
    }

    public function atualizar(Request $request, $id)
    {
        $user = $request->user();

        $dadosCategoria = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update(array_merge($dadosCategoria, ['atualizado_por' => $user->id]));

        return response()->json($categoria, 200);
    }

    public function excluir($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->update(['ativo' => false]);
        return response()->json(['message' => 'Categoria excluída com sucesso'], 200);
    }
}
