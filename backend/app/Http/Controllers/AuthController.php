<?php

namespace App\Http\Controllers;

use App\Models\Role;
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

        // default role: student
        $studentRole = Role::where('slug', 'student')->first();
        if ($studentRole) {
            $user->roles()->syncWithoutDetaching([$studentRole->id]);
        }

        Auth::login($user);

        return response()->json(['status' => 'success']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json(['status' => 'success']);  
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
    }

    public function user() {
        $u = Auth::user();
        if (!$u) {
            return null;
        }
        return $u->load('roles');
    }

    public function logout() {
        Auth::logout();
        return response()->json(['status' => 'success']);
    }
}
