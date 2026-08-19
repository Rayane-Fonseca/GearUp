<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario; // <-- Importação crucial
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Importação crucial para verificar a senha
use Illuminate\Validation\ValidationException; // <-- Importação crucial para o erro de validação

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Corrigido: Agora o validate() é chamado corretamente a partir da requisição ($request)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        // Verifica se o usuário existe e se a senha está correta
        if (!$usuario || !Hash::check($request->password, $usuario->senha)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        // Cria um token de acesso seguro para o usuário
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'sucesso' => true,
            'token' => $token,
            'token_type' => 'Bearer',
            'usuario' => [
                'id' => $usuario->id_usuario,
                'nome' => $usuario->nome,
                'email' => $usuario->email,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Revoga (deleta) todos os tokens do usuário atual
        $request->user()->tokens()->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Logout realizado com sucesso. Tokens revogados.'
        ]);
    }
}