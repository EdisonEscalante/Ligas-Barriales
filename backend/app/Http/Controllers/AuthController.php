<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * AuthController
 * Maneja la autenticación de usuarios del sistema
 * Registro, login y logout mediante tokens de API
 */
class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario en el sistema
     * POST /api/auth/registro
     */
    public function registro(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Creamos el usuario con la contraseña encriptada
        $user = User::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generamos el token de acceso para la API
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'mensaje' => 'Usuario registrado correctamente',
            'usuario' => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * Inicia sesión y devuelve un token de acceso
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Verificamos las credenciales
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'mensaje' => 'Credenciales incorrectas'
            ], 401);
        }

        // Eliminamos tokens anteriores y generamos uno nuevo
        $user->tokens()->delete();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'mensaje' => 'Sesión iniciada correctamente',
            'usuario' => $user,
            'token'   => $token,
        ]);
    }

    /**
     * Cierra la sesión del usuario eliminando su token
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        // Eliminamos el token actual del usuario
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente'
        ]);
    }

    /**
     * Devuelve los datos del usuario autenticado con sus roles
     * GET /api/auth/perfil
     */
    public function perfil(Request $request)
    {
        $user = User::with('roles.rol')->findOrFail($request->user()->id);
        return response()->json($user);
    }
}