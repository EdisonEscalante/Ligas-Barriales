{{-- 
    Componente reutilizable de ubicación para Ecuador
    Uso: <x-ubicacion :provincia="$provincia" :canton="$canton" :parroquia="$parroquia" />
    Los tres selectores son encadenados: provincia → cantón → parroquia
--}}

@props([
    'provincia' => '',
    'canton'    => '',
    'parroquia' => '',
])

{{-- Selector de Provincia --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Provincia *</label>
    <select name="provincia" id="provincia" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Seleccione una provincia...</option>
    </select>
</div>

{{-- Selector de Cantón --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Cantón *</label>
    <select name="canton" id="canton" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Seleccione un cantón...</option>
    </select>
</div>

{{-- Selector de Parroquia --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Parroquia *</label>
    <select name="parroquia" id="parroquia" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Seleccione una parroquia...</option>
    </select>
</div>

{{-- Script para cargar los datos encadenados --}}
<script>
    // Valores preseleccionados (para edición)
    const valorProvincia = "{{ $provincia }}";
    const valorCanton    = "{{ $canton }}";
    const valorParroquia = "{{ $parroquia }}";

    // Cargamos el JSON con las ubicaciones del Ecuador
    fetch('/js/ecuador.json')
        .then(res => res.json())
        .then(data => {
            const selProvincia = document.getElementById('provincia');
            const selCanton    = document.getElementById('canton');
            const selParroquia = document.getElementById('parroquia');

            // Cargamos las provincias
            Object.keys(data).sort().forEach(provincia => {
                const opt = new Option(provincia, provincia);
                if (provincia === valorProvincia) opt.selected = true;
                selProvincia.add(opt);
            });

            // Función para cargar cantones según provincia seleccionada
            function cargarCantones(provincia, cantonSeleccionado = '') {
                selCanton.innerHTML = '<option value="">Seleccione un cantón...</option>';
                selParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';

                if (!provincia || !data[provincia]) return;

                Object.keys(data[provincia]).sort().forEach(canton => {
                    const opt = new Option(canton, canton);
                    if (canton === cantonSeleccionado) opt.selected = true;
                    selCanton.add(opt);
                });
            }

            // Función para cargar parroquias según cantón seleccionado
            function cargarParroquias(provincia, canton, parroquiaSeleccionada = '') {
                selParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';

                if (!provincia || !canton || !data[provincia] || !data[provincia][canton]) return;

                data[provincia][canton].sort().forEach(parroquia => {
                    const opt = new Option(parroquia, parroquia);
                    if (parroquia === parroquiaSeleccionada) opt.selected = true;
                    selParroquia.add(opt);
                });
            }

            // Si hay valores preseleccionados (modo edición) los cargamos
            if (valorProvincia) {
                cargarCantones(valorProvincia, valorCanton);
                if (valorCanton) {
                    cargarParroquias(valorProvincia, valorCanton, valorParroquia);
                }
            }

            // Eventos de cambio
            selProvincia.addEventListener('change', function() {
                cargarCantones(this.value);
            });

            selCanton.addEventListener('change', function() {
                cargarParroquias(selProvincia.value, this.value);
            });
        });
</script>