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

                <x-input name="serial" label="Serial" autocomplete="off" required />

                {{-- <x-select name="brand_model_id" label="Marca / Modelo" :options="$brandModels->pluck('name','id')" required /> --}}
                    
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Marca / Modelo *</label>
    <select name="brand_model_id" required 
        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white @error('brand_model_id') border-red-500 @enderror">
        <option value="">Selecciona el modelo del equipo</option>
        
        @foreach($brandModels->groupBy('brand.name') as $brandName => $models)
            <optgroup label="{{ $brandName }}">
                @foreach($models as $model)
                    <option value="{{ $model->id }}" {{ old('brand_model_id') == $model->id ? 'selected' : '' }}>
                        {{ $brandName }} — {{ $model->name }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
    @error('brand_model_id')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
                    <x-input name="hostname" label="Hostname" autocomplete="off"/>

                <x-input name="processor" label="Procesador" autocomplete="off" />
                <x-input name="ram" label="RAM" autocomplete="off" />

                <x-select name="operating_system_id" label="Sistema operativo"
                          :options="$operatingSystems->pluck('name','id')" />
{{-- 
                <x-select name="department_id" label="Departamento"
                          :options="$departments->pluck('name','id')" />

                <x-select name="company_id" label="Empresa"
                          :options="$companies->pluck('name','id')" /> --}}
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Empresa / Departamento del Equipo *</label>
    <select name="company_and_department" required 
        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
        <option value="">Selecciona la ubicación y el área</option>
        @foreach($companies as $company)
            <optgroup label="{{ $company->name }}">
                @foreach($company->departments as $dept)
                    <option value="{{ $company->id }}-{{ $dept->id }}" {{ old('company_and_department') == ($company->id . '-' . $dept->id) ? 'selected' : '' }}>
                        {{ $company->name }} — {{ $dept->name }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
</div>

{{-- <div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Empleado asignado</label>
    <select name="employee_id" id="employee_select"
        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Selecciona primero una Empresa / Departamento</option>
    </select>
</div>
                           --}}
                          {{-- <x-select name="employee_id" label="Empleado"
                          :options="$employees->mapWithKeys(fn($e)=>[$e->id => $e->first_name.' '.$e->last_name])" /> --}}
                          
                          {{-- Select de Empleado (Ejemplo nativo para asegurar compatibilidad con JS) --}}
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Empleado asignado</label>
    <select name="employee_id" id="employee_select"
        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm bg-white">
        <option value="">Selecciona primero una Empresa / Departamento</option>
        
        @if(isset($computer) && $computer->employee)
            @foreach($employees as $e)
                        @if($e->company_id == $computer->company_id && $e->department_id == $computer->department_id)
                <option value="{{ $e->id }}" {{ $computer->employee_id == $e->id ? 'selected' : '' }}>
                    {{ $e->last_name }}, {{ $e->first_name }}
                </option>
            @endif 
            @endforeach
        @endif
    </select>
</div>
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

<div class="bg-white rounded-lg border border-slate-200 p-6 shadow-xs">
    <h2 class="text-sm font-semibold text-slate-700 mb-5">Garantía</h2>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Vendedor / Proveedor</label>
            <input type="text" name="seller" value="{{ old('seller') }}" autocomplete="off"
                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white @error('seller') border-red-500 @enderror">
            @error('seller') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Orden de Compra</label>
            <input type="text" name="purchase_order" value="{{ old('purchase_order') }}" autocomplete="off"
                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white @error('purchase_order') border-red-500 @enderror">
            @error('purchase_order') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Inicio de Garantía</label>
            <input type="date" name="warranty_start_date" value="{{ old('warranty_start_date') }}"
                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white @error('warranty_start_date') border-red-500 @enderror">
            @error('warranty_start_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Fin de Garantía</label>
            <input type="date" name="warranty_end_date" value="{{ old('warranty_end_date') }}"
                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white @error('warranty_end_date') border-red-500 @enderror">
            @error('warranty_end_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">PDF de Orden de Compra</label>
            <input type="file" name="purchase_order_pdf" accept="application/pdf"
                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-300 rounded-md p-1 bg-white @error('purchase_order_pdf') border-red-500 @enderror">
            @error('purchase_order_pdf') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
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
document.addEventListener('DOMContentLoaded', function () {
    const employeeSelect = document.querySelector('select[name="employee_id"]');
    const statusSelect = document.querySelector('select[name="status"]');

    if (employeeSelect && statusSelect) {
        
        employeeSelect.addEventListener('change', function () {
            if (this.value !== "") {
                statusSelect.value = 'assigned';
            } else {
                if (statusSelect.value === 'assigned') {
                    statusSelect.value = 'stock';
                }
            }
        });

        statusSelect.addEventListener('change', function () {
            if (this.value === 'stock' || this.value === 'faulty' || this.value === 'obsolete') {
                if (this.value === 'stock') {
                    employeeSelect.value = "";
                }
            } else if (this.value === 'assigned') {
                if (employeeSelect.value === "") {
                    employeeSelect.focus();
                }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const locationSelect = document.querySelector('select[name="company_and_department"]');
    const employeeSelect = document.getElementById('employee_select');

    if (locationSelect && employeeSelect) {
        locationSelect.addEventListener('change', function () {
            const combinedValue = this.value; 

            if (!combinedValue) {
                employeeSelect.innerHTML = '<option value="">Selecciona primero una Empresa / Departamento</option>';
                return;
            }

            fetch(`/api/employees-by-location?location=${combinedValue}`)
                .then(response => response.json())
                .then(employees => {
                    employeeSelect.innerHTML = '<option value="">Sin asignar</option>';
                    
                    employees.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = `${employee.last_name}, ${employee.first_name}`;
                        employeeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error cargando empleados:', error));
        });
    }
});
</script>

@endsection