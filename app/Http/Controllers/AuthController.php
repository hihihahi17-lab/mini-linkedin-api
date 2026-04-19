<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'in:candidat,recruteur'
            
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'role'     => $data['role'] ?? 'candidat',
        ]);

        $token = auth()->login($user);

        return response()->json([
            'message' => 'Compte créé avec succès',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

  
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'user'    => auth()->user(),
            'token'   => $token
        ]);
    }

    // تسجيل الخروج
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Déconnecté avec succès'
        ]);
    }

   
    public function me()
    {
        return response()->json(auth()->user());
    }
}