<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required'
            ]
        ]);

        if (!Auth::attempt($credentials, false)){
            return response([
                'error' => 'Los datos proporcionados no son correctos'
            ], 422);
        }

        $user = Auth::user();
        $data = [
            'email' => $user->email,
            'hour' => Carbon::now()->format('Hmi'),
        ];
        $token = $user->createToken('main', ['*'], $data)->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response([
            'success' => true
        ]);
    }


}
