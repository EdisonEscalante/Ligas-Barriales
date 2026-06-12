@extends('layouts.app')

@section('title', 'Ligas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-blue-800">Ligas Registradas</h2>
    <a href="/ligas/create"
       class="bg-blue-800 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        + Nueva Liga
    </a>
</div>

{{-- Tabla de ligas --}}
@if($ligas->count() > 0)
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Nombre</th>
                    <th class="px-4 py-3 text-left">Ciudad</th>
                    <th class="px-4 py-3 text-left">Cantón</th>
                    <th class="px-4 py-3 text-left">Provincia</th>
                    <th class="px-4 py-3 text-left">Fundación</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($ligas as $liga)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $liga->nombre }}</td>
                    <td class="px-4 py-3">{{ $liga->ciudad }}</td>
                    <td class="px-4 py-3">{{ $liga->canton }}</td>
                    <td class="px-4 py-3">{{ $liga->provincia }}</td>
                    <td class="px-4 py-3">{{ $liga->fecha_fundacion ?? '—' }}</td>
                    <td class="px-4 py-3 text-center flex justify-center gap-2">
                        {{-- Ver --}}
                        <a href="/ligas/{{ $liga->id }}"
                           class="bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200">
                            Ver
                        </a>
                        {{-- Editar --}}
                        <a href="/ligas/{{ $liga->id }}/edit"
                           class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200">
                            Editar
                        </a>
                        {{-- Eliminar --}}
                        <form action="/ligas/{{ $liga->id }}" method="POST"
                              onsubmit="return confirm('¿Estás seguro de eliminar esta liga?')">
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
    </div>
@else
    <div class="bg-white rounded-2xl shadow p-8 text-center text-gray-500">
        No hay ligas registradas aún.
        <a href="/ligas/create" class="text-blue-600 hover:underline ml-1">Crear la primera liga</a>
    </div>
@endif
@endsection