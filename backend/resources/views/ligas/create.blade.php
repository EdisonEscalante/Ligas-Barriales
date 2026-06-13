@extends('layouts.app')

@section('title', 'Nueva Liga')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-800">Nueva Liga</h2>
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

        {{-- enctype necesario para subir archivos --}}
        <form action="/ligas" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Nombre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la liga *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ej: Liga Barrial El Inca" required>
            </div>

            {{-- Fecha de fundación --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de fundación</label>
                <input type="date" name="fecha_fundacion" value="{{ old('fecha_fundacion') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Componente de ubicación encadenado --}}
            <x-ubicacion
                :provincia="old('provincia', '')"
                :canton="old('canton', '')"
                :parroquia="old('parroquia', '')"
            />

            {{-- Barrio / Cooperativa --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barrio / Cooperativa</label>
                <input type="text" name="barrio" value="{{ old('barrio') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ej: Barrio San Luis, Cooperativa La Unión">
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Descripción de la liga...">{{ old('descripcion') }}</textarea>
            </div>

            {{-- Escudo desde PC --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Escudo de la liga</label>
                <div class="flex items-center gap-4">
                    {{-- Vista previa de la imagen --}}
                    <div id="preview-container" class="w-20 h-20 rounded-full border-2 border-gray-300 flex items-center justify-center bg-gray-50 overflow-hidden">
                        <span id="preview-placeholder" class="text-3xl">🏆</span>
                        <img id="preview-img" src="" alt="Escudo" class="hidden w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="escudo" id="escudo" accept="image/*"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG o GIF. Máximo 2MB.</p>
                    </div>
                </div>
            </div>

            {{-- Sección administrador --}}
            <div class="border-t pt-4 mt-4">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">👤 Administrador de la Liga</h3>
                <p class="text-sm text-gray-500 mb-4">Se creará un usuario administrador para gestionar esta liga.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                        <input type="text" name="admin_nombre" value="{{ old('admin_nombre') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nombre del administrador" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico *</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="correo@ejemplo.com" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña temporal *</label>
                        <input type="password" name="admin_password"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Mínimo 8 caracteres" required>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-800 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Guardar Liga
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
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-img').classList.remove('hidden');
                document.getElementById('preview-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection