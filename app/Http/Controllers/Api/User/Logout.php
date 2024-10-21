<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Logout extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        try {
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }

    }
}
