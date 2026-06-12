@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">

        {{-- Logo y título --}}
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-blue-800">⚽ Ligas Barriales</h1>
            <p class="text-gray-500 mt-1">Inicia sesión para continuar</p>
        </div>

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Formulario de login --}}
        <form action="/login" method="POST" class="space-y-4">
            @csrf

            {{-- Campo email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Correo electrónico
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="correo@ejemplo.com"
                    required
                >
            </div>

            {{-- Campo password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Contraseña
                </label>
                <input
                    type="password"
                    name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="••••••••"
                    required
                >
            </div>

            {{-- Botón de login --}}
            <button
                type="submit"
                class="w-full bg-blue-800 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Iniciar sesión
            </button>
        </form>
    </div>
</div>
@endsection