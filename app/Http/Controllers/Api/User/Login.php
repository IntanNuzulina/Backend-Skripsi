<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('username', 'password'))) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => 'error'
                ], 401);
            }

            $user = User::where('username', $request->username)->firstOrFail();

            $token = $user->createToken($request->username)->plainTextToken;

            $cookie = $this->getCookie($token);

            return response()->json([
                'status' => 'success',
                'message' => 'Login success',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ])->withCookie($cookie);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    private function getCookie($token)
    {
        return cookie(
            'alhikmah-token',
            strval($token),
            60 * 24 * 30,
            null,
            null,
            env('APP_DEBUG') ? false : true,
            true,
            false,
            'Strict'
        );
    }
}
