@extends('layouts.app')

@section('title', 'Nueva garantía')
@section('header', 'Nueva garantía')

@section('content')

<div class="max-w-4xl bg-white rounded-lg border border-slate-200 p-6">

    <form action="{{ route('warranties.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <x-input 
                name="warranty_code" 
                label="Código de garantía"
                placeholder="Ej. GAR-2026-001"
                required
            />

            <x-input 
                name="provider" 
                label="Proveedor"
                placeholder="Ej. Dell, HP, Lenovo"
                required
            />

            <x-input 
                type="date"
                name="start_date"
                label="Fecha inicio"
                required
            />

            <x-input 
                type="date"
                name="end_date"
                label="Fecha finalización"
                required
            />

        </div>


        <div class="border-t border-slate-200 pt-5">

            <label class="block text-sm font-medium text-slate-700 mb-1">
                Tipo de equipo
            </label>

            <select 
                name="warrantable_type"
                id="warrantable_type"
                required
                class="w-full rounded-md border-slate-300 bg-slate-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border"
            >
                <option value="">-- Seleccionar tipo --</option>

                @foreach ($equipmentTypes as $classNamespace => $label)

                    <option 
                        value="{{ $classNamespace }}"
                        {{ old('warrantable_type') == $classNamespace ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>

                @endforeach

            </select>

        </div>



        <div class="relative space-y-2">

            <label class="block text-sm font-medium text-slate-700">
                Buscar equipos
            </label>


            <input
                type="text"
                id="search_device"
                disabled
                placeholder="Seleccione primero un tipo de equipo..."
                class="w-full rounded-md border-slate-300 bg-slate-50 shadow-sm p-2 border"
                required
            >


            <div 
                id="search_results"
                class="hidden absolute z-20 w-full bg-white border border-slate-200 rounded-md shadow-lg max-h-52 overflow-y-auto"
            >
            </div>

        </div>




        <div>

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Equipos asociados 
                (<span id="basket_count">0</span>)
            </label>


            <div 
                id="selected_devices_basket"
                class="rounded-md border border-dashed border-slate-300 bg-slate-50 p-4 min-h-24 space-y-2"
            >

                <p 
                    id="basket_empty_placeholder"
                    class="text-sm text-slate-400 italic text-center py-3"
                >
                    No hay equipos asociados todavía.
                </p>

            </div>

        </div>




        <div>

            <label class="block text-sm font-medium text-slate-700 mb-1">
                Notas
            </label>


            <textarea
                name="notes"
                rows="4"
                class="w-full rounded-md border-slate-300 bg-slate-50 shadow-sm p-2 border focus:border-indigo-500 focus:ring-indigo-500"
            >{{ old('notes') }}</textarea>

        </div>

<div>

    <label class="block text-sm font-medium text-slate-700 mb-1">
        Documento de garantía (PDF)
    </label>


    <input
        type="file"
        name="document"
        accept="application/pdf"
        class="w-full rounded-md border-slate-300 bg-slate-50 shadow-sm p-2 border"
    >


    <p class="text-xs text-slate-400 mt-1">
        Solo archivos PDF. Máximo 10MB.
    </p>

</div>


        <div class="flex items-center gap-3 pt-4 border-t border-slate-200">

            <x-button>
                Guardar garantía
            </x-button>


            <x-button-secondary :href="route('warranties.index')">
                Cancelar
            </x-button-secondary>

        </div>


    </form>

</div>



<script>
document.addEventListener('DOMContentLoaded', function () {

    const typeSelect = document.getElementById('warrantable_type');
    const searchInput = document.getElementById('search_device');
    const resultsContainer = document.getElementById('search_results');

    const basketContainer = document.getElementById('selected_devices_basket');
    const basketPlaceholder = document.getElementById('basket_empty_placeholder');
    const basketCount = document.getElementById('basket_count');


    let allDevicesCache = [];
    let selectedDevicesSet = new Map();



    async function fetchTypeInventory(type)
    {

        if(!type)
        {
            searchInput.disabled=true;
            return;
        }


        searchInput.disabled=true;
        searchInput.placeholder="Cargando equipos...";


        const response = await fetch(
            `{{ route('warranties.devices') }}?type=${encodeURIComponent(type)}`
        );


        allDevicesCache = await response.json();


        searchInput.disabled=false;
        searchInput.placeholder="Buscar por serial o nombre...";

    }




    searchInput.addEventListener('input',function(){

        let query=this.value.toLowerCase().trim();


        if(!query)
        {
            resultsContainer.classList.add('hidden');
            return;
        }


        let matches=allDevicesCache.filter(device=>{

            return (
                device.label.toLowerCase().includes(query)
                ||
                device.dynamic_serial.toLowerCase().includes(query)
            )
            &&
            !selectedDevicesSet.has(device.id);

        });



        resultsContainer.innerHTML='';


        matches.slice(0,10).forEach(device=>{


            let row=document.createElement('div');

            row.className=
            "px-3 py-2 hover:bg-indigo-50 cursor-pointer border-b border-slate-100 text-sm flex justify-between";


            row.innerHTML=`

                <span>
                    <b>${device.label}</b>
                    <small class="text-slate-400">
                    (${device.dynamic_serial})
                    </small>
                </span>

                <span class="text-indigo-600 font-semibold">
                    + Agregar
                </span>

            `;


            row.onclick=()=>{

                addDevice(device);

                searchInput.value='';
                resultsContainer.classList.add('hidden');

            };


            resultsContainer.appendChild(row);


        });


        resultsContainer.classList.remove('hidden');

    });




    function addDevice(device)
    {

        if(selectedDevicesSet.has(device.id))
            return;


        selectedDevicesSet.set(device.id,device);


        basketPlaceholder.classList.add('hidden');


        let item=document.createElement('div');

        item.className=
        "flex justify-between items-center bg-white border border-slate-200 rounded-md px-3 py-2 text-sm";


        item.innerHTML=`

            <input 
            type="hidden"
            name="warrantable_ids[]"
            value="${device.id}"
            >

            <span>
            ${device.label}
            <small class="text-slate-400">
            (${device.dynamic_serial})
            </small>
            </span>


            <button 
            type="button"
            class="text-red-500 font-bold">
            ✕
            </button>

        `;


        item.querySelector('button').onclick=()=>{

            selectedDevicesSet.delete(device.id);
            item.remove();
            updateBasket();

        };


        basketContainer.appendChild(item);

        updateBasket();

    }




    function updateBasket()
    {

        basketCount.textContent =
        selectedDevicesSet.size;


        if(selectedDevicesSet.size===0)
            basketPlaceholder.classList.remove('hidden');

    }




    typeSelect.addEventListener('change',function(){

        selectedDevicesSet.clear();

        basketContainer
        .querySelectorAll('div')
        .forEach(e=>e.remove());


        updateBasket();

        fetchTypeInventory(this.value);

    });
if (typeSelect.value) {
        fetchTypeInventory(typeSelect.value);
    }

});
</script>


@endsection