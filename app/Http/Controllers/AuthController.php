<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\ResponseErrorService;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (is_null($user) || !Hash::check($password, $user->password)) {
            return ResponseErrorService::json('User or password are invalid', JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = JWT::encode(['email' => $email], env('JWT_KEY'), 'HS256');

        return response()->json(['access_token' => $token]);
    }
}