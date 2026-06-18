@extends('layouts.app')

@section('title', 'Mi Liga')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-800">Mi Liga</h2>
        <a href="/mi-liga/edit"
           class="bg-blue-800 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            Editar Liga
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center gap-4 mb-6">
            {{-- Escudo de la liga --}}
            <div class="w-24 h-24 rounded-full border-2 border-gray-200 flex items-center justify-center overflow-hidden bg-gray-50">
                @if($liga->escudo_url)
                    <img src="{{ asset('storage/' . $liga->escudo_url) }}" alt="Escudo"
                         class="w-full h-full object-cover">
                @else
                    <span class="text-4xl">🏆</span>
                @endif
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $liga->nombre }}</h3>
                <p class="text-gray-500">
                    Fundada el {{ $liga->fecha_fundacion ?? 'No registrada' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400">Provincia</p>
                <p class="font-medium text-gray-800">{{ $liga->provincia }}</p>
            </div>
            <div>
                <p class="text-gray-400">Cantón</p>
                <p class="font-medium text-gray-800">{{ $liga->canton }}</p>
            </div>
            <div>
                <p class="text-gray-400">Parroquia</p>
                <p class="font-medium text-gray-800">{{ $liga->parroquia }}</p>
            </div>
            <div>
                <p class="text-gray-400">Barrio / Cooperativa</p>
                <p class="font-medium text-gray-800">{{ $liga->barrio ?? '—' }}</p>
            </div>
        </div>

        @if($liga->descripcion)
            <div class="mt-6 pt-4 border-t">
                <p class="text-gray-400 text-sm mb-1">Descripción</p>
                <p class="text-gray-700">{{ $liga->descripcion }}</p>
            </div>
        @endif
    </div>
</div>
@endsection