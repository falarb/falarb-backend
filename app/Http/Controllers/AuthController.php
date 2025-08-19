<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginAdministrador(Request $request)
    {
        $credentials = $request->only('email', 'senha');

        $authCredentials = [
            'email' => $credentials['email'],
            'password' => $credentials['senha']
        ];

        if (!Auth::attempt($authCredentials)) {
            return response()->json(['erro' => 'Não autorizado'], 401);
        }

        $user = Auth::user();

        if (!$user->ativo) {
            return response()->json(['erro' => 'Usuário inativo'], 403);
        }

        $token = $user->createToken('tokenAdmin')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }
}
