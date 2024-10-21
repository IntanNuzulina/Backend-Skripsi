<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Create extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_hp' => ['required'],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'repassword' => ['required', 'string', 'min:8', 'same:password'],
        ],[
            'name.required' => 'Name harus diisi',
            'username.required' => 'Username harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimum 8 karakter',
            'repassword.min' => 'Password minimum 8 karakter',
        ]);

        try {

            $users = User::create(
                $request->except('repassword')
            );

            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil ditambahkan',
                'data' => $users
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
