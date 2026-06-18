<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Liga;
use App\Models\UsuarioRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * MiLigaWebController
 * Permite al Administrador de Liga ver y editar
 * únicamente la liga que tiene asignada
 */
class MiLigaWebController extends Controller
{
    /**
     * Obtiene la liga asignada al usuario autenticado
     */
    private function obtenerMiLiga()
    {
        $usuarioRol = UsuarioRol::where('user_id', Auth::id())
            ->whereHas('rol', function($q) {
                $q->where('nombre', 'admin_liga');
            })
            ->first();

        if (!$usuarioRol || !$usuarioRol->liga_id) {
            abort(404, 'No tienes una liga asignada.');
        }

        return Liga::findOrFail($usuarioRol->liga_id);
    }

    /**
     * Muestra los detalles de la liga del administrador
     * GET /mi-liga
     */
    public function show()
    {
        $liga = $this->obtenerMiLiga();
        return view('mi-liga.show', compact('liga'));
    }

    /**
     * Muestra el formulario para editar la liga
     * GET /mi-liga/edit
     */
    public function edit()
    {
        $liga = $this->obtenerMiLiga();
        return view('mi-liga.edit', compact('liga'));
    }

    /**
     * Actualiza los datos de la liga
     * PUT /mi-liga
     */
    public function update(Request $request)
    {
        $liga = $this->obtenerMiLiga();

        $request->validate([
            'nombre'          => 'required|string|max:255',
            'fecha_fundacion' => 'nullable|date',
            'provincia'       => 'required|string|max:100',
            'canton'          => 'required|string|max:100',
            'parroquia'       => 'required|string|max:100',
            'barrio'          => 'nullable|string|max:255',
            'descripcion'     => 'nullable|string',
            'escudo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $escudoPath = $liga->escudo_url;
        if ($request->hasFile('escudo')) {
            if ($liga->escudo_url) {
                Storage::disk('public')->delete($liga->escudo_url);
            }
            $escudoPath = $request->file('escudo')->store('escudos', 'public');
        }

        $liga->update([
            'nombre'          => ucwords(strtolower($request->nombre)),
            'fecha_fundacion' => $request->fecha_fundacion,
            'provincia'       => ucwords(strtolower($request->provincia)),
            'ciudad'          => ucwords(strtolower($request->canton)),
            'canton'          => ucwords(strtolower($request->canton)),
            'parroquia'       => ucwords(strtolower($request->parroquia)),
            'barrio'          => $request->barrio ? ucwords(strtolower($request->barrio)) : null,
            'descripcion'     => $request->descripcion,
            'escudo_url'      => $escudoPath,
        ]);

        return redirect('/mi-liga')->with('success', 'Información de tu liga actualizada correctamente');
    }
}