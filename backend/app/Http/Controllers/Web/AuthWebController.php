<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * AuthWebController
 * Maneja la autenticación de usuarios en la interfaz web
 * Usa sesiones de Laravel en lugar de tokens (a diferencia de la API)
 */
class AuthWebController extends Controller
{
    /**
     * Muestra el formulario de login
     * GET /login
     */
    public function showLogin()
    {
        // Si ya está autenticado lo redirigimos al dashboard
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa el formulario de login
     * POST /login
     */
    public function login(Request $request)
    {
        // Validamos los datos del formulario
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Intentamos autenticar al usuario
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Bienvenido ' . Auth::user()->nombre);
        }

        // Si las credenciales son incorrectas regresamos al login con error
        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->withInput();
    }

    /**
     * Cierra la sesión del usuario
     * GET /auth/logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Sesión cerrada correctamente');
    }
}