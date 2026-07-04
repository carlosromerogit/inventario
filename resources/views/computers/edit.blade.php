@extends('layouts.app')

@section('title', 'Editar equipo')
@section('header', 'Editar equipo: ' . $computer->serial)

@section('content')
<form action="{{ route('computers.update', $computer) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="space-y-6">

        {{-- ================= DATOS ================= --}}
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-5">Datos del equipo</h2>

            <div class="grid grid-cols-2 gap-5">

                <x-select name="brand_model_id" label="Marca / Modelo"
                    :options="$brandModels->mapWithKeys(fn($m) => [$m->id => $m->brand->name . ' — ' . $m->name])"
                    :selected="old('brand_model_id', $computer->brand_model_id)"
                    required />

                <x-input name="serial" label="Número de serie" required
                    :value="old('serial', $computer->serial)" />

                <x-input name="hostname" label="Hostname"
                    :value="old('hostname', $computer->hostname)" />

                <x-input name="processor" label="Procesador"
                    :value="old('processor', $computer->processor)" />

                <x-input name="ram" label="RAM"
                    :value="old('ram', $computer->ram)" />

                <x-select name="operating_system_id" label="Sistema operativo"
                    :options="$operatingSystems->pluck('name', 'id')"
                    :selected="old('operating_system_id', $computer->operating_system_id)" />

                <x-select name="department_id" label="Departamento"
                    :options="$departments->pluck('name', 'id')"
                    :selected="old('department_id', $computer->department_id)" />

                {{-- 🔥 NUEVO: EMPRESA --}}
                <x-select name="company_id" label="Empresa"
                    :options="$companies->pluck('name', 'id')"
                    :selected="old('company_id', $computer->company_id)" />

             

                    <x-select name="employee_id" label="Empleado asignado"
                        :options="$employees->mapWithKeys(fn($e) => [$e->id => $e->last_name . ', ' . $e->first_name])"
                        :selected="old('employee_id', $computer->employee_id)"
                        placeholder="Sin asignar" />

                   <x-input name="fixed_asset" label="Activo fijo"
                    :value="old('fixed_asset', $computer->fixed_asset)" />

                   {{-- 🔥 NUEVO: ESTADO --}}
                <x-select name="status" label="Estado"
                    :options="[
                         'stock' => 'En stock',
                        'assigned' => 'Asignado',
                        'faulty' => 'Averiado',
                        'obsolete' => 'Obsoleto'
                    ]"
                    :selected="old('status', $computer->status)" />

            </div>
        </div>

        {{-- ================= DISCOS ================= --}}

        {{-- ================= DISCOS ================= --}}
<div class="bg-white rounded-lg border border-slate-200 p-6 shadow-xs">

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-sm font-semibold text-slate-700">Discos</h2>

        <button type="button"
                onclick="addDrive()"
                class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
            + Agregar disco
        </button>
    </div>

    {{-- Discos ya instalados --}}
    @if($computer->drives->isNotEmpty())
        <div class="mb-6 space-y-3">

            @foreach($computer->drives as $drive)

                <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">

                    <div>
                        <p class="font-medium text-slate-800">
                            {{ $drive->driveType->name }}
                            —
                            {{ $drive->brandModel->brand->name }}
                            {{ $drive->brandModel->name }}
                        </p>

                        <p class="text-sm text-slate-500">
                            {{ $drive->formatted_capacity }}
                        </p>
                    </div>

                    <div class="flex gap-3">

                        <a href="{{ route('drives.edit', $drive) }}"
                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            Editar
                        </a>

                        <button type="button"
                                onclick="if(confirm('¿Eliminar este disco?')) document.getElementById('delete-drive-{{ $drive->id }}').submit();"
                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Eliminar
                        </button>

                    </div>

                </div>

            @endforeach

        </div>
    @endif

    {{-- Discos nuevos --}}
    <div id="drives-container" class="space-y-3"></div>

    <p id="drives-empty" class="text-sm text-slate-400 text-center py-4">
        Sin discos nuevos agregados
    </p>

</div>
 
        {{-- ================= IMÁGENES ================= --}}
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Imágenes del equipo</h2>

            @if($computer->images->isNotEmpty())
                <div class="grid grid-cols-6 gap-3 mb-6">
                    @foreach($computer->images as $img)
                        <div class="aspect-square rounded-md overflow-hidden border border-slate-200">
                            <img src="{{ asset('storage/' . $img->path) }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif

            <button type="button"
                    onclick="document.getElementById('images-input').click()"
                    class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                + Agregar imágenes
            </button>

            <input type="file" id="images-input" name="images[]" multiple class="hidden"
                   onchange="handleImageSelect(this)">

            <div id="image-preview-container" class="grid grid-cols-6 gap-3 hidden mt-3"></div>

            <p id="images-empty" class="text-sm text-slate-400 text-center py-3">
                No hay nuevas imágenes seleccionadas.
            </p>
        </div>

        {{-- ================= BOTONES ================= --}}
        <div class="flex gap-3">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md">
                Guardar cambios
            </button>

            <a href="{{ route('computers.show', $computer) }}"
               class="px-4 py-2 border rounded-md">
                Cancelar
            </a>
        </div>

    </div>
</form>
{{-- Formularios para eliminar discos --}}
@foreach($computer->drives as $drive)
<form id="delete-drive-{{ $drive->id }}"
      action="{{ route('drives.destroy', $drive) }}"
      method="POST"
      class="hidden">
    @csrf
    @method('DELETE')
</form>
@endforeach
<script>
/* ===========================================================
|  DISCOS
=========================================================== */

let driveIndex = 0;

function addDrive(prefilled = null) {

    const i = driveIndex++;

    const container = document.getElementById('drives-container');
    document.getElementById('drives-empty').style.display = 'none';

    const row = document.createElement('div');

    row.className =
        "drive-row grid grid-cols-5 gap-4 p-4 bg-white rounded-lg border border-slate-200 items-end";

    row.innerHTML = `
        <select name="drives[${i}][drive_type_id]"
            class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition">
            <option value="">Tipo</option>
            @foreach($driveTypes as $t)
                <option value="{{ $t->id }}">{{ $t->name }}</option>
            @endforeach
        </select>

        <select name="drives[${i}][brand_model_id]"
            class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition">
            <option value="">Modelo</option>
            @foreach($driveModels as $m)
                <option value="{{ $m->id }}">{{ $m->brand->name }} - {{ $m->name }}</option>
            @endforeach
        </select>

        <input
            type="number"
            name="drives[${i}][cap_number]"
            placeholder="Capacidad"
            min="1"
            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition">

        <select
            name="drives[${i}][cap_unit]"
            class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition">
            <option>GB</option>
            <option>TB</option>
            <option>MB</option>
        </select>

        <div class="flex items-center justify-end h-10 pb-1">
            <button
                type="button"
                onclick="removeDrive(this)"
                class="text-red-600 hover:text-red-800 text-xs font-semibold px-2 py-1 rounded-md hover:bg-red-50 transition-colors">
                Quitar
            </button>
        </div>
    `;

    container.appendChild(row);
}

function removeDrive(button) {

    const row = button.closest('.drive-row');

    if (!row) return;

    row.remove();

    const container = document.getElementById('drives-container');

    if (container.children.length === 0) {
        document.getElementById('drives-empty').style.display = 'block';
    }
}


/* ===========================================================
|  IMÁGENES
=========================================================== */

let selectedFiles = [];

function handleImageSelect(input) {

    Array.from(input.files).forEach(function(file) {

        if (!file.type.startsWith('image/')) {
            return;
        }

        const index = selectedFiles.length;

        selectedFiles.push(file);

        renderPreview(file, index);
    });

    input.value = '';

    syncFiles();
}

function renderPreview(file, index) {

    const reader = new FileReader();

    reader.onload = function(e) {

        const container = document.getElementById('image-preview-container');

        container.classList.remove('hidden');

        document.getElementById('images-empty').style.display = 'none';

        const wrap = document.createElement('div');

        wrap.className =
            'relative aspect-square rounded-md overflow-hidden border border-slate-200 bg-slate-100';

        wrap.id = 'preview-' + index;

        const img = document.createElement('img');

        img.src = e.target.result;

        img.className = 'w-full h-full object-cover';

        const btn = document.createElement('button');

        btn.type = 'button';

        btn.innerHTML = '&times;';

        btn.className =
            'absolute top-1 right-1 flex items-center justify-center w-5 h-5 rounded-full bg-red-600 text-white text-xs font-bold leading-none hover:bg-red-500 transition';

        btn.onclick = function() {
            removePreview(index);
        };

        wrap.appendChild(img);
        wrap.appendChild(btn);

        container.appendChild(wrap);
    };

    reader.readAsDataURL(file);
}

function removePreview(index) {

    selectedFiles[index] = null;

    const el = document.getElementById('preview-' + index);

    if (el) {
        el.remove();
    }

    syncFiles();

    const active = selectedFiles.filter(function(file) {
        return file !== null;
    });

    if (active.length === 0) {

        document
            .getElementById('image-preview-container')
            .classList.add('hidden');

        document.getElementById('images-empty').style.display = 'block';
    }
}

function syncFiles() {

    const dt = new DataTransfer();

    selectedFiles.forEach(function(file) {

        if (file !== null) {
            dt.items.add(file);
        }

    });

    document.getElementById('images-input').files = dt.files;
}
</script>
     @endsection