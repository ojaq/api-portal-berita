<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request -> validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        // dd($user);
        if (!$user || ! Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'account' => ['The provided credentials are incorrect']
            ]);
        }

        return $user->createToken('user logged in')->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'logged out'
        ]);
    }

    public function me(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
