<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $jwt_key = config('app.jwt_key');
        $payload = ['base_url' => url('/'), 'user_id' => 1];
        $token = JWT::encode($payload, $jwt_key, 'HS256');

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => 1,
                'name' => 'Abu Hasan Shadhin'
            ],
        ]);
    }
}
