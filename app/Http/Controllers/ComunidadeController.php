<?php

namespace App\Http\Controllers;

use App\Models\Comunidade;
use Illuminate\Http\Request;

class ComunidadeController extends Controller
{
    public function listar()
    {
        $comunidades = Comunidade::all();
        return response()->json($comunidades, 200);
    }

    public function visualizar($id)
    {
        $comunidade = Comunidade::findOrFail($id);
        return response()->json($comunidade, 200);
    }

    public function criar(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $comunidade = Comunidade::create($data);
        return response()->json($comunidade, 201);
    }
}
