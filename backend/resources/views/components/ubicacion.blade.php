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

<script>
    // Valores preseleccionados normalizados a mayúsculas para comparar
    const valorProvincia = "{{ strtoupper($provincia) }}";
    const valorCanton    = "{{ strtoupper($canton) }}";
    const valorParroquia = "{{ strtoupper($parroquia) }}";

    fetch('/js/ecuador.json')
        .then(res => res.json())
        .then(data => {
            const selProvincia = document.getElementById('provincia');
            const selCanton    = document.getElementById('canton');
            const selParroquia = document.getElementById('parroquia');

            // Cargamos las provincias
            Object.keys(data).sort().forEach(provincia => {
                const opt = new Option(provincia, provincia);
                // Comparamos en mayúsculas para que coincida sin importar el formato
                if (provincia.toUpperCase() === valorProvincia) opt.selected = true;
                selProvincia.add(opt);
            });

            // Función para cargar cantones
            function cargarCantones(provincia, cantonSeleccionado = '') {
                selCanton.innerHTML = '<option value="">Seleccione un cantón...</option>';
                selParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';

                // Buscamos la provincia sin importar mayúsculas
                const provinciaKey = Object.keys(data).find(p => p.toUpperCase() === provincia.toUpperCase());
                if (!provinciaKey) return;

                Object.keys(data[provinciaKey]).sort().forEach(canton => {
                    const opt = new Option(canton, canton);
                    if (canton.toUpperCase() === cantonSeleccionado.toUpperCase()) opt.selected = true;
                    selCanton.add(opt);
                });
            }

            // Función para cargar parroquias
            function cargarParroquias(provincia, canton, parroquiaSeleccionada = '') {
                selParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';

                const provinciaKey = Object.keys(data).find(p => p.toUpperCase() === provincia.toUpperCase());
                if (!provinciaKey) return;

                const cantonKey = Object.keys(data[provinciaKey]).find(c => c.toUpperCase() === canton.toUpperCase());
                if (!cantonKey) return;

                data[provinciaKey][cantonKey].sort().forEach(parroquia => {
                    const opt = new Option(parroquia, parroquia);
                    if (parroquia.toUpperCase() === parroquiaSeleccionada.toUpperCase()) opt.selected = true;
                    selParroquia.add(opt);
                });
            }

            // Si hay valores preseleccionados los cargamos
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