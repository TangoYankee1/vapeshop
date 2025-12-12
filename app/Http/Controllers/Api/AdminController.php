<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                $token = $user->createToken('admin-token', ['role:admin'])->plainTextToken;
                return response()->json(['token' => $token]);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
