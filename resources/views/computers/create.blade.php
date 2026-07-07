@extends('layouts.app')

@section('title', 'Nuevo equipo')
@section('header', 'Nuevo equipo')

@section('content')

<form action="{{ route('computers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="space-y-6">

        {{-- ================= DATOS ================= --}}
        <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-xs">
            <h2 class="text-sm font-semibold text-slate-700 mb-5">Datos del equipo</h2>

            <div class="grid grid-cols-2 gap-5">

                <x-input name="serial" label="Serial" autocomplete="off" required />
                <x-select name="brand_model_id" label="Marca / Modelo" :options="$brandModels->pluck('name','id')" required />
                <x-input name="hostname" label="Hostname" autocomplete="off"/>

                <x-input name="processor" label="Procesador" autocomplete="off" />
                <x-input name="ram" label="RAM" autocomplete="off" />

                <x-select name="operating_system_id" label="Sistema operativo"
                          :options="$operatingSystems->pluck('name','id')" />

                <x-select name="department_id" label="Departamento"
                          :options="$departments->pluck('name','id')" />

                <x-select name="company_id" label="Empresa"
                          :options="$companies->pluck('name','id')" />

                          
                          <x-select name="employee_id" label="Empleado"
                          :options="$employees->mapWithKeys(fn($e)=>[$e->id => $e->first_name.' '.$e->last_name])" />
                          
                        <x-input name="fixed_asset" label="Activo fijo" autocomplete="off"/>
                          
                          <x-select 
                        name="status" 
                        label="Estado"
                        :options="[
                            'stock' => 'En stock',
                            'assigned' => 'Asignado',
                            'faulty' => 'Averiado',
                            'obsolete' => 'Obsoleto'
                        ]"/>

            </div>
        </div>

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

    <div id="drives-container" class="space-y-3"></div>

    <p id="drives-empty" class="text-sm text-slate-400 text-center py-4">
        Sin discos agregados
    </p>
</div>
        {{-- ================= IMÁGENES ================= --}}
        <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-xs">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-700">Imágenes</h2>

                <button type="button"
                        onclick="document.getElementById('images-input').click()"
                        class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                    + Agregar imágenes
                </button>
            </div>

            <input type="file" id="images-input" name="images[]" multiple class="hidden" autocomplete="off"
                   onchange="handleImageSelect(this)">

            <div id="image-preview-container" class="grid grid-cols-6 gap-3 hidden"></div>

            <p id="images-empty" class="text-sm text-slate-400 text-center py-4">
                No hay imágenes
            </p>
        </div>

        {{-- ================= BOTONES ================= --}}
        <div class="flex gap-3">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md">
                Guardar equipo
            </button>

            <a href="{{ route('computers.index') }}"
               class="px-4 py-2 border rounded-md">
                Cancelar
            </a>
        </div>

    </div>
</form>

{{-- ================= JS DISCOS ================= --}}
<script>
let driveIndex = 0;

function addDrive(prefilled = null) {

    const i = driveIndex++;
    const container = document.getElementById('drives-container');
    document.getElementById('drives-empty').style.display = 'none';

    const row = document.createElement('div');
    row.className = "drive-row grid grid-cols-5 gap-4 p-4 bg-white rounded-lg border border-slate-200 items-end";
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

    <input type="number"
        name="drives[${i}][cap_number]"
        placeholder="Capacidad"
        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition">

    <select name="drives[${i}][cap_unit]"
        class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition">
        <option>GB</option>
        <option>TB</option>
        <option>MB</option>
    </select>
<div class="flex items-center justify-end h-10 pb-1">
    <button type="button"
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

    if (!row) {
        console.error("No se encontró .drive-row");
        return;
    }

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
            'absolute top-1 right-1 flex items-center justify-center w-5 h-5 rounded-full bg-red-600 text-white text-xs font-bold hover:bg-red-500 transition';

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

    const preview = document.getElementById('preview-' + index);

    if (preview) {
        preview.remove();
    }

    syncFiles();

    if (selectedFiles.filter(f => f !== null).length === 0) {
        document.getElementById('image-preview-container').classList.add('hidden');
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