<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Ligas Barriales' }}</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-CYPI_2au.css') }}">
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Barra de navegación principal --}}
    {{-- Barra de navegación principal --}}
<nav class="bg-blue-800 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <a href="/dashboard" class="text-xl font-bold tracking-wide">⚽ Ligas Barriales</a>

        <div class="flex gap-6 text-sm items-center">

            @auth
                {{-- Menú para Super Administrador --}}
                @if($rolActual === 'super_admin')
                    <a href="/ligas" class="hover:text-yellow-300">Ligas</a>
                    <a href="/usuarios" class="hover:text-yellow-300">Usuarios</a>
                @endif

                {{-- Menú para Administrador de Liga --}}
                @if($rolActual === 'admin_liga')
                    <a href="/mi-liga" class="hover:text-yellow-300">Mi Liga</a>
                    <a href="/temporadas" class="hover:text-yellow-300">Temporadas</a>
                    <a href="/disciplinas" class="hover:text-yellow-300">Disciplinas</a>
                    <a href="/equipos" class="hover:text-yellow-300">Equipos</a>
                    <a href="/jugadores" class="hover:text-yellow-300">Jugadores</a>
                    <a href="/torneos" class="hover:text-yellow-300">Torneos</a>
                    <a href="/usuarios-liga" class="hover:text-yellow-300">Mi Equipo de Trabajo</a>
                @endif

                {{-- Menú para Calificador --}}
                @if($rolActual === 'calificador')
                    <a href="/calificaciones" class="hover:text-yellow-300">Calificaciones</a>
                @endif

                {{-- Menú para Programador --}}
                @if($rolActual === 'programador')
                    <a href="/programacion" class="hover:text-yellow-300">Programación</a>
                @endif

                {{-- Menú para Sancionador --}}
                @if($rolActual === 'sancionador')
                    <a href="/sanciones" class="hover:text-yellow-300">Sanciones</a>
                @endif

                {{-- Indicador de la liga actual si aplica --}}
                @if($ligaActual)
                    <span class="text-yellow-300 text-xs bg-blue-900 px-3 py-1 rounded-full">
                        🏆 {{ $ligaActual->nombre }}
                    </span>
                @endif

                <a href="/auth/logout" class="hover:text-red-300">Cerrar sesión</a>
            @else
                <a href="/login" class="hover:text-yellow-300">Iniciar sesión</a>
            @endauth
        </div>
    </div>
</nav>

    {{-- Contenido principal de cada página --}}
    <main class="max-w-7xl mx-auto px-4 py-8">
        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensajes de error --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Aquí se inserta el contenido de cada vista --}}
        @yield('content')
    </main>

    {{-- Pie de página --}}
    <footer class="bg-blue-900 text-white text-center py-4 mt-8 text-sm">
        © {{ date('Y') }} Ligas Barriales — Sistema de Gestión Deportiva
    </footer>

</body>
</html>