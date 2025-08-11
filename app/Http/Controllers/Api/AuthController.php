<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->failure('Credenciais inválidas', Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return $this->failure('Não foi possível criar o token', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->success(
            message: 'Login realizado com sucesso',
            data: ['token' => $token],
            status: Response::HTTP_OK
        );
    }
}
