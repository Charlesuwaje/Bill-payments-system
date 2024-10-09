<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $response = $this->authService->login($credentials);

        if (isset($response['error'])) {
            return response()->json(['message' => $response['error']], $response['status']);
        }

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $response = $this->authService->logout($request->user());

        return response()->json($response);
    }
}