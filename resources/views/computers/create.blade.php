@extends('layouts.app')

@section('title', 'Nuevo equipo')
@section('header', 'Nuevo equipo')

@section('content')
<form action="{{ route('computers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="space-y-6">

        <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-xs">
            <h2 class="text-sm font-semibold text-slate-700 mb-5">Datos del equipo</h2>
            <div class="grid grid-cols-2 gap-5">
                
                <div>
                    <label for="brand_model_id" class="block text-sm font-medium text-slate-700 mb-1">
                        Marca / Modelo <span class="text-red-500">*</span>
                    </label>
                    <select name="brand_model_id" id="brand_model_id" required
                            class="block w-full rounded-md border bg-white py-2 pl-3 pr-10 text-sm text-slate-800 shadow-xs transition duration-150 ease-in-out focus:ring-1 focus:outline-hidden cursor-pointer @error('brand_model_id') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                        <option value="">Seleccionar modelo...</option>
                        @foreach($brandModels as $model)
                            <option value="{{ $model->id }}" {{ old('brand_model_id') == $model->id ? 'selected' : '' }}>
                                {{ $model->brand->name }} — {{ $model->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_model_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="serial" class="block text-sm font-medium text-slate-700 mb-1">
                        Número de serie <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="serial" id="serial" value="{{ old('serial') }}" required placeholder="Ej. SN-00123"
                           class="block w-full rounded-md border text-sm px-3 py-2 text-slate-800 placeholder-slate-400 shadow-xs transition duration-150 ease-in-out focus:ring-1 focus:outline-hidden @error('serial') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                    @error('serial') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="processor" class="block text-sm font-medium text-slate-700 mb-1">Procesador</label>
                    <input type="text" name="processor" id="processor" value="{{ old('processor') }}" placeholder="Ej. Intel Core i5-10400"
                           class="block w-full rounded-md border text-sm px-3 py-2 text-slate-800 placeholder-slate-400 shadow-xs transition duration-150 ease-in-out focus:ring-1 focus:outline-hidden @error('processor') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                    @error('processor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ram" class="block text-sm font-medium text-slate-700 mb-1">RAM</label>
                    <input type="text" name="ram" id="ram" value="{{ old('ram') }}" placeholder="Ej. 16GB DDR4"
                           class="block w-full rounded-md border text-sm px-3 py-2 text-slate-800 placeholder-slate-400 shadow-xs transition duration-150 ease-in-out focus:ring-1 focus:outline-hidden @error('ram') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                    @error('ram') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="operating_system_id" class="block text-sm font-medium text-slate-700 mb-1">Sistema operativo</label>
                    <select name="operating_system_id" id="operating_system_id"
                            class="block w-full rounded-md border bg-white py-2 pl-3 pr-10 text-sm text-slate-800 shadow-xs transition duration-150 ease-in-out focus:ring-1 focus:outline-hidden cursor-pointer @error('operating_system_id') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                        <option value="">Seleccionar S.O...</option>
                        @foreach($operatingSystems as $os)
                            <option value="{{ $os->id }}" {{ old('operating_system_id') == $os->id ? 'selected' : '' }}>
                                {{ $os->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('operating_system_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="department_id" class="block text-sm font-medium text-slate-700 mb-1">Departamento</label>
                    <select name="department_id" id="department_id"
                            class="block w-full rounded-md border bg-white py-2 pl-3 pr-10 text-sm text-slate-800 shadow-xs transition duration-150 ease-in-out focus:ring-1 focus:outline-hidden cursor-pointer @error('department_id') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                        <option value="">Seleccionar departamento...</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2">
                    <label for="employee_id" class="block text-sm font-medium text-slate-700 mb-1">Empleado asignado</label>
                    <select name="employee_id" id="employee_id"
                            class="block w-full rounded-md border bg-white py-2 pl-3 pr-10 text-sm text-slate-800 shadow-xs transition duration-150 ease-in-out focus:ring-1 focus:outline-hidden cursor-pointer @error('employee_id') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                        <option value="">Sin asignar</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->last_name }}, {{ $employee->first_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-xs">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-semibold text-slate-700">Discos <span class="text-slate-400 font-normal">(opcional)</span></h2>
                <button type="button" onclick="addDrive()"
                        class="inline-flex items-center rounded-md bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                    + Agregar disco
                </button>
            </div>
            <div id="drives-container" class="space-y-3"></div>
            <p id="drives-empty" class="text-sm text-slate-400 text-center py-4 select-none">
                Opcional — puedes agregar discos ahora o desde el detalle del equipo.
            </p>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-700">Imágenes <span class="text-slate-400 font-normal">(opcional)</span></h2>
                <button type="button" onclick="document.getElementById('images-input').click()"
                        class="inline-flex items-center rounded-md bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                    + Seleccionar imágenes
                </button>
            </div>

            <input type="file" id="images-input" name="images[]" multiple accept="image/*"
                   class="hidden" onchange="handleImageSelect(this)">

            <div id="image-preview-container" class="grid grid-cols-4 gap-3 sm:grid-cols-6 lg:grid-cols-8 mb-3 hidden"></div>

            <p id="images-empty" class="text-sm text-slate-400 text-center py-4 select-none">
                No se han seleccionado imágenes todavía.
            </p>

            <p class="text-xs text-slate-400 select-none">PNG, JPG o WEBP. Máximo 4 MB por imagen.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" 
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 shadow-sm transition cursor-pointer">
                Registrar equipo
            </button>
            <a href="{{ route('computers.index') }}" 
               class="rounded-md bg-white border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                Cancelar
            </a>
        </div>
    </div>
</form>

<script>
var driveIndex = 0;
var driveTypes  = @json($driveTypes->map(fn($d) => ['id' => $d->id, 'name' => $d->name]));
var driveModels = @json($driveModels->map(fn($m) => ['id' => $m->id, 'name' => $m->brand->name . ' — ' . $m->name]));

function buildSelect(name, options, selectedValue = '') {
    var select = document.createElement('select');
    select.name = name;
    select.required = true;
    select.className = 'block w-full rounded-md border border-slate-300 bg-white py-2 pl-3 pr-10 text-sm text-slate-800 shadow-xs transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden cursor-pointer';
    var ph = document.createElement('option');
    ph.value = ''; ph.textContent = 'Seleccionar...';
    select.appendChild(ph);
    options.forEach(function(opt) {
        var o = document.createElement('option');
        o.value = opt.id; o.textContent = opt.name;
        if(selectedValue == opt.id) o.selected = true;
        select.appendChild(o);
    });
    return select;
}

function buildField(labelText, innerElement) {
    var div = document.createElement('div');
    var label = document.createElement('label');
    label.className = 'block text-sm font-medium text-slate-700 mb-1';
    label.innerHTML = labelText + ' <span class="text-red-500">*</span>';
    div.appendChild(label);
    div.appendChild(innerElement);
    return div;
}

function addDrive(prefilled = null) {
    var i = driveIndex++;
    var container = document.getElementById('drives-container');
    document.getElementById('drives-empty').style.display = 'none';

    var row = document.createElement('div');
    row.className = 'grid grid-cols-4 gap-4 p-4 bg-slate-50 rounded-md border border-slate-200';
    row.id = 'drive-row-' + i;

    var typeVal = prefilled ? prefilled.drive_type_id : '';
    var modelVal = prefilled ? prefilled.brand_model_id : '';
    var numVal = prefilled ? prefilled.cap_number : '';
    var unitVal = prefilled ? prefilled.cap_unit : 'GB';

    row.appendChild(buildField('Tipo', buildSelect('drives[' + i + '][drive_type_id]', driveTypes, typeVal)));
    row.appendChild(buildField('Marca / Modelo', buildSelect('drives[' + i + '][brand_model_id]', driveModels, modelVal)));

    var capacityGroup = document.createElement('div');
    capacityGroup.className = 'grid grid-cols-3 gap-1.5';

    var numInput = document.createElement('input');
    numInput.type = 'number';
    numInput.name = 'drives[' + i + '][cap_number]';
    numInput.placeholder = 'Ej. 512';
    numInput.required = true;
    numInput.min = '1';
    numInput.value = numVal;
    numInput.className = 'col-span-2 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden';
    capacityGroup.appendChild(numInput);

    var unitSelect = document.createElement('select');
    unitSelect.name = 'drives[' + i + '][cap_unit]';
    unitSelect.className = 'block w-full rounded-md border border-slate-300 bg-white py-2 pl-3 pr-10 text-sm text-slate-800 shadow-xs transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden cursor-pointer';
    ['GB', 'TB', 'MB'].forEach(function(unit) {
        var opt = document.createElement('option');
        opt.value = unit; opt.textContent = unit;
        if(unitVal === unit) opt.selected = true;
        unitSelect.appendChild(opt);
    });
    capacityGroup.appendChild(unitSelect);

    row.appendChild(buildField('Capacidad', capacityGroup));

    var removeDiv = document.createElement('div');
    removeDiv.className = 'flex items-end';
    var btn = document.createElement('button');
    btn.type = 'button'; btn.textContent = 'Quitar';
    btn.className = 'text-red-500 hover:text-red-700 text-xs font-semibold mb-2.5 transition cursor-pointer';
    btn.onclick = function() { removeDrive(i); };
    removeDiv.appendChild(btn);
    row.appendChild(removeDiv);

    container.appendChild(row);
}

function removeDrive(i) {
    var row = document.getElementById('drive-row-' + i);
    if (row) row.remove();
    if (document.getElementById('drives-container').children.length === 0) {
        document.getElementById('drives-empty').style.display = 'block';
    }
}

document.addEventListener("DOMContentLoaded", function() {
    var oldDrives = @json(old('drives', []));
    Object.values(oldDrives).forEach(function(drive) {
        addDrive(drive);
    });
});

var selectedFiles = [];

function handleImageSelect(input) {
    Array.from(input.files).forEach(function(file) {
        if (!file.type.startsWith('image/')) return;
        var index = selectedFiles.length;
        selectedFiles.push(file);
        renderPreview(file, index);
    });
    input.value = '';
    syncFiles();
}

function renderPreview(file, index) {
    var reader = new FileReader();
    reader.onload = function(e) {
        var container = document.getElementById('image-preview-container');
        container.classList.remove('hidden');
        document.getElementById('images-empty').style.display = 'none';

        var wrap = document.createElement('div');
        wrap.className = 'relative aspect-square rounded-md overflow-hidden border border-slate-200 bg-slate-100';
        wrap.id = 'preview-' + index;

        var img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'w-full h-full object-cover';

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.innerHTML = '&times;';
        btn.className = 'absolute top-1 right-1 flex items-center justify-center w-5 h-5 rounded-full bg-red-600 text-white text-xs font-bold leading-none hover:bg-red-500 transition cursor-pointer';
        btn.onclick = function() { removePreview(index); };

        wrap.appendChild(img);
        wrap.appendChild(btn);
        container.appendChild(wrap);
    };
    reader.readAsDataURL(file);
}

function removePreview(index) {
    selectedFiles[index] = null;
    var el = document.getElementById('preview-' + index);
    if (el) el.remove();
    syncFiles();
    var active = selectedFiles.filter(function(f) { return f !== null; });
    if (active.length === 0) {
        document.getElementById('image-preview-container').classList.add('hidden');
        document.getElementById('images-empty').style.display = 'block';
    }
}

function syncFiles() {
    var dt = new DataTransfer();
    selectedFiles.forEach(function(file) {
        if (file !== null) dt.items.add(file);
    });
    document.getElementById('images-input').files = dt.files;
}
</script>
@endsection