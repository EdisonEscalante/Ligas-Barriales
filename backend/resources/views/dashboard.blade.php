@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-800">Bienvenido, {{ Auth::user()->nombre }}</h2>
    <p class="text-gray-500 mt-1">
        @if($rolActual === 'super_admin')
            Panel de administración general del sistema
        @elseif($rolActual === 'admin_liga')
            Panel de administración de {{ $ligaActual->nombre ?? 'tu liga' }}
        @else
            Panel de gestión
        @endif
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    {{-- Tarjetas para Super Administrador --}}
    @if($rolActual === 'super_admin')
        <a href="/ligas" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
            <div class="text-4xl mb-3">🏆</div>
            <h3 class="text-lg font-semibold text-gray-800">Ligas</h3>
            <p class="text-gray-500 text-sm">Gestiona las ligas barriales</p>
        </a>

        <a href="/usuarios" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
            <div class="text-4xl mb-3">👥</div>
            <h3 class="text-lg font-semibold text-gray-800">Usuarios</h3>
            <p class="text-gray-500 text-sm">Administra los usuarios del sistema</p>
        </a>
    @endif

    {{-- Tarjetas para Administrador de Liga --}}
    @if($rolActual === 'admin_liga')
        <a href="/mi-liga" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
            <div class="text-4xl mb-3">🏆</div>
            <h3 class="text-lg font-semibold text-gray-800">Mi Liga</h3>
            <p class="text-gray-500 text-sm">Información de tu liga</p>
        </a>

        <a href="/temporadas" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
            <div class="text-4xl mb-3">📅</div>
            <h3 class="text-lg font-semibold text-gray-800">Temporadas</h3>
            <p class="text-gray-500 text-sm">Gestiona las temporadas</p>
        </a>

        <a href="/disciplinas" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
            <div class="text-4xl mb-3">🏅</div>
            <h3 class="text-lg font-semibold text-gray-800">Disciplinas</h3>
            <p class="text-gray-500 text-sm">Deportes y categorías</p>
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
            <div class="text-4xl mb-3">🏟️</div>
            <h3 class="text-lg font-semibold text-gray-800">Torneos</h3>
            <p class="text-gray-500 text-sm">Gestiona los torneos</p>
        </a>

        <a href="/usuarios-liga" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
            <div class="text-4xl mb-3">🧑‍💼</div>
            <h3 class="text-lg font-semibold text-gray-800">Mi Equipo de Trabajo</h3>
            <p class="text-gray-500 text-sm">Calificadores, programadores, etc.</p>
        </a>
    @endif

    {{-- Tarjeta para Calificador --}}
    @if($rolActual === 'calificador')
        <a href="/calificaciones" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
            <div class="text-4xl mb-3">📊</div>
            <h3 class="text-lg font-semibold text-gray-800">Calificaciones</h3>
            <p class="text-gray-500 text-sm">Registra resultados y estadísticas</p>
        </a>
    @endif

    {{-- Tarjeta para Programador --}}
    @if($rolActual === 'programador')
        <a href="/programacion" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
            <div class="text-4xl mb-3">📅</div>
            <h3 class="text-lg font-semibold text-gray-800">Programación</h3>
            <p class="text-gray-500 text-sm">Calendario de partidos</p>
        </a>
    @endif

    {{-- Tarjeta para Sancionador --}}
    @if($rolActual === 'sancionador')
        <a href="/sanciones" class="bg-white rounded-2xl shadow p-6 hover:shadow-md transition">
            <div class="text-4xl mb-3">🟨</div>
            <h3 class="text-lg font-semibold text-gray-800">Sanciones</h3>
            <p class="text-gray-500 text-sm">Amonestaciones y suspensiones</p>
        </a>
    @endif

</div>
@endsection