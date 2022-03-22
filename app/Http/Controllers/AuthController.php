<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\ResponseErrorService;
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
            return ResponseErrorService::json('Wrong credentials', JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('user');

        return response()->json([
            'access_token' => $token->accessToken,
            'expires_at' => $token->token->expires_at
        ]);
    }
}