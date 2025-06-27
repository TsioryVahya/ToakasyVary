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
            'username' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }
        return back()->withErrors(['username' => 'Identifiants invalides.']);
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
        return redirect('/home');
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