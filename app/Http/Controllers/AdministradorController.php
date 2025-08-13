<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Hash;
use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    public function criar(Request $request)
    {
        $administrador = Administrador::create($request->all());
        return response()->json($administrador, 201);
    }

    public function listar()
    {
        $administradores = Administrador::all();
        return response()->json($administradores, 200);
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
