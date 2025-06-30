<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Connexion réussie !');
        }
        return back()->withErrors(['email' => 'Identifiants invalides.'])->withInput();
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $data = $request->validate([
            'name' => 'required|unique:users,name|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
            'id_employe' => 'nullable|exists:employes,id',
        ]);
        $user = User::create([
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']), // Utilisez 'password' au lieu de 'pswd'
                'id_employe' => $data['id_employe'] ?? null, // Utilisez la valeur du formulaire ou null
            ])
        ]);
        Auth::login($user);
        return redirect('/home')->with('success', 'Inscription réussie !');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function home() {
        return view('home');
    }
}