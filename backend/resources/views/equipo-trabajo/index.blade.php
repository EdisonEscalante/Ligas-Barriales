@extends('layouts.app')

@section('title', 'Mi Equipo de Trabajo')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-blue-800">Mi Equipo de Trabajo</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Formulario para crear nuevo usuario auxiliar --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">+ Nuevo Colaborador</h3>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="/usuarios-liga" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nombre del colaborador" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico *</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="correo@ejemplo.com" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña temporal *</label>
                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Mínimo 8 caracteres" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol *</label>
                    <select name="rol_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione un rol...</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}">
                                {{ ucwords(str_replace('_', ' ', $rol->nombre)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-blue-800 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Crear Colaborador
                </button>
            </form>
        </div>
    </div>

    {{-- Lista de colaboradores actuales --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            @if($usuariosLiga->count() > 0)
                <table class="w-full text-sm">
                    <thead class="bg-blue-800 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Nombre</th>
                            <th class="px-4 py-3 text-left">Correo</th>
                            <th class="px-4 py-3 text-left">Rol</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($usuariosLiga as $usuarioRol)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $usuarioRol->usuario->nombre }}</td>
                            <td class="px-4 py-3">{{ $usuarioRol->usuario->email }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                    {{ ucwords(str_replace('_', ' ', $usuarioRol->rol->nombre)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="/usuarios-liga/{{ $usuarioRol->usuario->id }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este colaborador?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-8 text-center text-gray-500">
                    Aún no tienes colaboradores registrados.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection