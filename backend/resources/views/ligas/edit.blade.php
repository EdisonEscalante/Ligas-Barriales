@extends('layouts.app')

@section('title', 'Editar Liga')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-800">Editar Liga</h2>
        <a href="/ligas" class="text-gray-500 hover:text-gray-700">← Volver</a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="/ligas/{{ $liga->id }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la liga *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $liga->nombre) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Fecha de fundación --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de fundación</label>
                <input type="date" name="fecha_fundacion" value="{{ old('fecha_fundacion', $liga->fecha_fundacion) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Componente de ubicación encadenado --}}
            <x-ubicacion
                :provincia="old('provincia', $liga->provincia)"
                :canton="old('canton', $liga->canton)"
                :parroquia="old('parroquia', $liga->parroquia)"
            />

            {{-- Barrio / Cooperativa --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barrio / Cooperativa</label>
                <input type="text" name="barrio" value="{{ old('barrio', $liga->barrio) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ej: Barrio San Luis, Cooperativa La Unión">
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $liga->descripcion) }}</textarea>
            </div>

            {{-- Escudo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Escudo de la liga</label>
                <div class="flex items-center gap-4">
                    {{-- Vista previa del escudo actual o nuevo --}}
                    <div class="w-20 h-20 rounded-full border-2 border-gray-300 flex items-center justify-center bg-gray-50 overflow-hidden">
                        @if($liga->escudo_url)
                            <img id="preview-img" src="{{ asset('storage/' . $liga->escudo_url) }}"
                                 alt="Escudo actual" class="w-full h-full object-cover">
                        @else
                            <span id="preview-placeholder" class="text-3xl">🏆</span>
                            <img id="preview-img" src="" alt="Escudo" class="hidden w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" name="escudo" id="escudo" accept="image/*"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Deja vacío para mantener el escudo actual. PNG, JPG o GIF. Máximo 2MB.</p>
                    </div>
                </div>
            </div>
            {{-- Sección para gestionar el administrador de la liga --}}
<div class="border-t pt-4 mt-4">
    <h3 class="text-lg font-semibold text-blue-800 mb-3">👤 Administrador de la Liga</h3>

    @if($administrador)
        <div class="bg-gray-50 rounded-lg p-3 mb-4 text-sm">
            <p class="text-gray-600">Administrador actual:</p>
            <p class="font-semibold text-gray-800">{{ $administrador->nombre }}</p>
            <p class="text-gray-500">{{ $administrador->email }}</p>
        </div>

        {{-- Opciones para el administrador --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">¿Qué deseas hacer?</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="admin_accion" value="mantener" checked
                        onchange="toggleAdminSections(this.value)">
                    <span class="text-sm">Mantener administrador actual</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="admin_accion" value="cambiar_password"
                        onchange="toggleAdminSections(this.value)">
                    <span class="text-sm">Cambiar contraseña</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="admin_accion" value="cambiar_admin"
                        onchange="toggleAdminSections(this.value)">
                    <span class="text-sm">Cambiar administrador</span>
                </label>
            </div>
        </div>

        {{-- Sección cambiar contraseña --}}
        <div id="seccion_password" class="hidden space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña *</label>
                <input type="password" name="admin_password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Mínimo 8 caracteres">
            </div>
        </div>

        {{-- Sección cambiar administrador --}}
        <div id="seccion_nuevo_admin" class="hidden space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                <input type="text" name="nuevo_admin_nombre"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nombre del nuevo administrador">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico *</label>
                <input type="email" name="nuevo_admin_email"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="correo@ejemplo.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña temporal *</label>
                <input type="password" name="nuevo_admin_password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Mínimo 8 caracteres">
            </div>
        </div>
    @else
        <p class="text-sm text-yellow-600 mb-4">⚠️ Esta liga no tiene administrador asignado.</p>
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                <input type="text" name="nuevo_admin_nombre"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nombre del administrador">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico *</label>
                <input type="email" name="nuevo_admin_email"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="correo@ejemplo.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña temporal *</label>
                <input type="password" name="nuevo_admin_password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Mínimo 8 caracteres">
            </div>
        </div>
    @endif
</div>


            {{-- Botones --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-800 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Actualizar Liga
                </button>
                <a href="/ligas"
                    class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Script para vista previa del escudo --}}
<script>
    document.getElementById('escudo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('preview-img');
                const placeholder = document.getElementById('preview-placeholder');
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection