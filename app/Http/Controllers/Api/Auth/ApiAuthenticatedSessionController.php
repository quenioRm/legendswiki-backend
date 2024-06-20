<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class ApiAuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        // Validar os dados de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Se a validação falhar, retornar os erros em formato JSON
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Tentar autenticar o usuário
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Gerar um token pessoal com expiração definida na coluna 'expires_at'
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user], 200);
        }

        // Se a autenticação falhar, lançar uma exceção de validação personalizada
        throw ValidationException::withMessages([
            'email' => [__('auth.failed')],
        ]);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->where('expires_at', '<', now())->delete(); // Exclui tokens expirados

        // Cria um novo token pessoal com nova expiração
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Destroy the authenticated session.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('sanctum')->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
