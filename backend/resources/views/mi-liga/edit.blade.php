@extends('layouts.app')

@section('title', 'Editar Mi Liga')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-800">Editar Mi Liga</h2>
        <a href="/mi-liga" class="text-gray-500 hover:text-gray-700">← Volver</a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="/mi-liga" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la liga *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $liga->nombre) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de fundación</label>
                <input type="date" name="fecha_fundacion" value="{{ old('fecha_fundacion', $liga->fecha_fundacion) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <x-ubicacion
                :provincia="old('provincia', $liga->provincia)"
                :canton="old('canton', $liga->canton)"
                :parroquia="old('parroquia', $liga->parroquia)"
            />

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barrio / Cooperativa</label>
                <input type="text" name="barrio" value="{{ old('barrio', $liga->barrio) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $liga->descripcion) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Escudo de la liga</label>
                <div class="flex items-center gap-4">
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
                        <p class="text-xs text-gray-400 mt-1">Deja vacío para mantener el escudo actual.</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-800 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Actualizar
                </button>
                <a href="/mi-liga"
                    class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

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