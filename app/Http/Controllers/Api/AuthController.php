<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($req->only('email','password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['user' => Auth::user()]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out']);
    }

    public function register(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => bcrypt($req->password),
        ]);

        return response()->json(['user' => $user]);
    }

    public function me(Request $request)
    {
        return $request->user();
    }
}
