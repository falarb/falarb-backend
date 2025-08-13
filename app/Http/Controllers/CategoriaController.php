<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function listar()
    {
        $categorias = Categoria::all();
        return response()->json($categorias, 200);
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
        $categoria->deletado_por = auth()->user()->id;
        $categoria->save();
        $categoria->delete();
        return response()->json(['message' => 'Categoria excluída com sucesso'], 200);
    }
}
