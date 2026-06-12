@extends('layouts.app')

@section('title', 'Nueva Liga')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-800">Nueva Liga</h2>
        <a href="/ligas" class="text-gray-500 hover:text-gray-700">← Volver</a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Formulario crear liga --}}
        <form action="/ligas" method="POST" class="space-y-4">
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

            {{-- Provincia y Ciudad --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provincia *</label>
                    <input type="text" name="provincia" value="{{ old('provincia') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ej: Pichincha" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ej: Quito" required>
                </div>
            </div>

            {{-- Cantón y Parroquia --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantón *</label>
                    <input type="text" name="canton" value="{{ old('canton') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ej: Quito" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parroquia *</label>
                    <input type="text" name="parroquia" value="{{ old('parroquia') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ej: El Inca" required>
                </div>
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Descripción de la liga...">{{ old('descripcion') }}</textarea>
            </div>

            {{-- Escudo URL --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL del escudo</label>
                <input type="text" name="escudo_url" value="{{ old('escudo_url') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="https://...">
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
@endsection