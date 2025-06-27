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
            return redirect()->intended('/home')->with('success', 'Connexion réussie !');
        }
        return back()->withErrors(['email' => 'Identifiants invalides.'])->withInput();
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $data = $request->validate([
            'username' => 'required|unique:users,username|min:3',
            'password' => 'required|min:4|confirmed',
        ]);
        $user = User::create([
            'username' => $data['username'],
            'pswd' => Hash::make($data['password']),
            'id_employe' => 1, // à adapter selon ta logique
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