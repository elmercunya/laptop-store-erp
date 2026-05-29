<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'user' => 'required|string',
            'password' => 'required|string'
        ]);

        if(Auth::attempt(['user' => $request->input('user'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            $tokenResult  = $user->createToken('api-token');

            return response()->json([
                'token' => $tokenResult->plainTextToken,
                'user' => $user->only(['id', 'user', 'role'])
            ]);
        }

        return response()->json([
            'message' => 'Credenciales inválidas',
            'status' => 'error'
        ], 422);
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Token revocado',
            'status' => 'success'
        ], 200);
    }

    public function me(Request $request) {
        $user = $request->user();
        return response()->json([
            'user' => $user->only(['id', 'user', 'role'])
        ]);
    }
        
}
