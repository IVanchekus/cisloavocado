<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        Auth::login($user);

        return response()->json(['status' => 'success']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        Auth::attempt($credentials);

        return response()->json(['status' => 'success']);
    }

    public function user() {
        return Auth::user();
    }
}
