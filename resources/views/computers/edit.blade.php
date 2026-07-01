@extends('layouts.app')

@section('title', 'Editar equipo')
@section('header', 'Editar equipo: ' . $computer->serial)

@section('content')
<form action="{{ route('computers.update', $computer) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="space-y-6">

        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-5">Datos del equipo</h2>
            <div class="grid grid-cols-2 gap-5">
                <x-select name="brand_model_id" label="Marca / Modelo"
                    :options="$brandModels->mapWithKeys(fn($m) => [$m->id => $m->brand->name . ' — ' . $m->name])"
                    :selected="old('brand_model_id', $computer->brand_model_id)"
                    required />
                <x-input name="serial" label="Número de serie" required placeholder="Ej. SN-00123" :value="old('serial', $computer->serial)" />
                <x-input name="processor" label="Procesador" placeholder="Ej. Intel Core i5-10400" :value="old('processor', $computer->processor)" />
                <x-input name="ram" label="RAM" placeholder="Ej. 16GB DDR4" :value="old('ram', $computer->ram)" />
                <x-select name="operating_system_id" label="Sistema operativo"
                    :options="$operatingSystems->pluck('name', 'id')" :selected="old('operating_system_id', $computer->operating_system_id)" placeholder="Seleccionar S.O..." />
                <x-select name="department_id" label="Departamento"
                    :options="$departments->pluck('name', 'id')" :selected="old('department_id', $computer->department_id)" placeholder="Seleccionar departamento..." />
                <div class="col-span-2">
                    <x-select name="employee_id" label="Empleado asignado"
                        :options="$employees->mapWithKeys(fn($e) => [$e->id => $e->last_name . ', ' . $e->first_name])"
                        :selected="old('employee_id', $computer->employee_id)"
                        placeholder="Sin asignar" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-semibold text-slate-700">Discos duros instalados</h2>
                <button type="button" onclick="addDrive()"
                        class="inline-flex items-center rounded-md bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                    + Agregar nuevo disco
                </button>
            </div>

            @if($computer->drives->isNotEmpty())
                <div class="mb-5 space-y-2">
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Discos actuales:</h3>
                    <div class="divide-y divide-slate-200 border border-slate-200 rounded-md bg-slate-50 p-2 space-y-2">
                        @foreach($computer->drives as $drive)
                            <div class="flex items-center justify-between py-2 px-3">
                                <span class="text-sm text-slate-700 font-medium">
                                    {{ $drive->driveType->name }} — {{ $drive->brandModel->brand->name }} {{ $drive->brandModel->name }} 
                                    <span class="text-slate-500 font-semibold">({{ $drive->formatted_capacity }})</span>
                                </span>
                                <div class="flex items-center gap-3 text-sm font-medium">
                                    <a href="{{ route('drives.edit', $drive) }}" class="text-indigo-600 hover:text-indigo-900">Editar pieza</a>
                                    
                                    <button type="button" 
                                            onclick="if(confirm('¿Eliminar este disco del inventario de este equipo?')) { document.getElementById('delete-drive-{{ $drive->id }}').submit(); }"
                                            class="text-red-600 hover:text-red-900">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div id="drives-container" class="space-y-3"></div>
            <p id="drives-empty" class="text-sm text-slate-400 text-center py-4">
                No hay nuevos discos agregados para este equipo en esta sesión.
            </p>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Imágenes del equipo</h2>
            
            @if($computer->images->isNotEmpty())
                <div class="grid grid-cols-6 gap-3 mb-6">
                    @foreach($computer->images as $img)
                        <div class="relative aspect-square rounded-md overflow-hidden border border-slate-200 bg-slate-50">
                            <img src="{{ asset('storage/' . $img->path) }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center justify-between mb-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Subir más imágenes</h3>
                <button type="button" onclick="document.getElementById('images-input').click()"
                        class="inline-flex items-center rounded-md bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                    + Seleccionar archivos
                </button>
            </div>

            <input type="file" id="images-input" name="images[]" multiple accept="image/*" class="hidden" onchange="handleImageSelect(this)">
            <div id="image-preview-container" class="grid grid-cols-4 gap-3 sm:grid-cols-6 lg:grid-cols-8 mb-3 hidden"></div>
            <p id="images-empty" class="text-sm text-slate-400 text-center py-2">No se han seleccionado nuevas imágenes.</p>
        </div>

        <div class="flex items-center gap-3">
            <x-button>Guardar cambios</x-button>
            <x-button-secondary :href="route('computers.show', $computer)">Cancelar</x-button-secondary>
        </div>
    </div>
</form>

@foreach($computer->drives as $drive)
    <form id="delete-drive-{{ $drive->id }}" action="{{ route('drives.destroy', $drive) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endforeach

<script>
var driveIndex = 0;
var driveTypes  = @json($driveTypes->map(fn($d) => ['id' => $d->id, 'name' => $d->name]));
var driveModels = @json($driveModels->map(fn($m) => ['id' => $m->id, 'name' => $m->brand->name . ' — ' . $m->name]));

function buildSelect(name, options) {
    var select = document.createElement('select');
    select.name = name;
    select.required = true;
    select.className = 'block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500';
    var ph = document.createElement('option');
    ph.value = ''; ph.textContent = 'Seleccionar...';
    select.appendChild(ph);
    options.forEach(function(opt) {
        var o = document.createElement('option');
        o.value = opt.id; o.textContent = opt.name;
        select.appendChild(o);
    });
    return select;
}

function buildField(labelText, innerElement) {
    var div = document.createElement('div');
    var label = document.createElement('label');
    label.className = 'block text-xs font-medium text-slate-600 mb-1';
    label.innerHTML = labelText + ' <span class="text-red-500">*</span>';
    div.appendChild(label);
    div.appendChild(innerElement);
    return div;
}

function addDrive() {
    var i = driveIndex++;
    var container = document.getElementById('drives-container');
    document.getElementById('drives-empty').style.display = 'none';

    var row = document.createElement('div');
    row.className = 'grid grid-cols-4 gap-4 p-4 bg-slate-50 rounded-md border border-slate-200';
    row.id = 'drive-row-' + i;

    row.appendChild(buildField('Tipo', buildSelect('drives[' + i + '][drive_type_id]', driveTypes)));
    row.appendChild(buildField('Marca / Modelo', buildSelect('drives[' + i + '][brand_model_id]', driveModels)));

    var capacityGroup = document.createElement('div');
    capacityGroup.className = 'grid grid-cols-3 gap-1.5';

    var numInput = document.createElement('input');
    numInput.type = 'number';
    numInput.name = 'drives[' + i + '][cap_number]';
    numInput.placeholder = 'Ej. 512';
    numInput.required = true;
    numInput.min = '1';
    numInput.className = 'col-span-2 block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500';
    capacityGroup.appendChild(numInput);

    var unitSelect = document.createElement('select');
    unitSelect.name = 'drives[' + i + '][cap_unit]';
    unitSelect.className = 'block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500';
    ['GB', 'TB', 'MB'].forEach(function(unit) {
        var opt = document.createElement('option');
        opt.value = unit; opt.textContent = unit;
        unitSelect.appendChild(opt);
    });
    capacityGroup.appendChild(unitSelect);

    row.appendChild(buildField('Capacity', capacityGroup));

    var removeDiv = document.createElement('div');
    removeDiv.className = 'flex items-end';
    var btn = document.createElement('button');
    btn.type = 'button'; btn.textContent = 'Quitar';
    btn.className = 'text-red-500 hover:text-red-700 text-xs font-medium mb-2';
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
        btn.type = 'button'; btn.innerHTML = '&times;';
        btn.className = 'absolute top-1 right-1 flex items-center justify-center w-5 h-5 rounded-full bg-red-600 text-white text-xs font-bold leading-none hover:bg-red-500 transition';
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