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

        // $accessToken = $user->createToken('authToken')->accessToken;
        Auth::login($user);

        return response(['user' => $user]);
    }

    public function login(Request $request)
    {
        dd($request);
        $credentials = $request->only('email', 'password');

        Auth::attempt($credentials);
        
        $user = Auth::user();
        $token = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $token]);
    }

    public function user() {
        dd(Auth::user());
        return Auth::user();
    }
}
