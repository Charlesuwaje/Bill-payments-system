<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return ['error' => 'Invalid login details', 'status' => 401];
        }

        $user = Auth::user();

        $token = $user->createToken('authToken')->plainTextToken;

        return [
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ];
    }

    public function logout($user)
    {
        $user->tokens()->delete();
        return ['message' => 'Logged out successfully'];
    }
}