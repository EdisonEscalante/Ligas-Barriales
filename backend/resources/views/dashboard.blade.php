@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-800">Bienvenido, {{ Auth::user()->nombre }}</h2>
    <p class="text-gray-500 mt-1">Panel de administración de Ligas Barriales</p>
</div>

{{-- Tarjetas de acceso rápido --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <a href="/ligas" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
        <div class="text-4xl mb-3">🏆</div>
        <h3 class="text-lg font-semibold text-gray-800">Ligas</h3>
        <p class="text-gray-500 text-sm">Gestiona las ligas barriales</p>
    </a>

    <a href="/equipos" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
        <div class="text-4xl mb-3">👕</div>
        <h3 class="text-lg font-semibold text-gray-800">Equipos</h3>
        <p class="text-gray-500 text-sm">Administra los equipos</p>
    </a>

    <a href="/jugadores" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
        <div class="text-4xl mb-3">⚽</div>
        <h3 class="text-lg font-semibold text-gray-800">Jugadores</h3>
        <p class="text-gray-500 text-sm">Registro de jugadores</p>
    </a>

    <a href="/torneos" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
        <div class="text-4xl mb-3">📅</div>
        <h3 class="text-lg font-semibold text-gray-800">Torneos</h3>
        <p class="text-gray-500 text-sm">Gestiona los torneos</p>
    </a>

</div>
@endsection