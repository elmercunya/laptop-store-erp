<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // Muestra el formulario de login
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        
        $credentials = $request->validate([
            'user' => 'required|string',
            'password' => 'required',
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if(Auth::user()->role === 'admin') {
                return redirect()->intended('dashboard');
            }

            return redirect()->intended(route('sales.index'));
                
        }

        return back()->withErrors([
            'user' => 'Las credenciales no coinciden con nuestros registros'
        ])->onlyInput('user');

    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
