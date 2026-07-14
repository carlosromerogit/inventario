@extends('layouts.app')

@section('title', 'Detalle de garantía')
@section('header', 'Detalle de garantía')

@section('content')

<div class="space-y-6">


    {{-- Header acciones --}}
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-xl font-semibold text-slate-900">
                {{ $warranty->warranty_code }}
            </h2>

            <p class="text-sm text-slate-500">
                Información completa de la garantía
            </p>
        </div>


        <div class="flex gap-3">

            <x-button-secondary :href="route('warranties.index')">
                Volver
            </x-button-secondary>


            <x-button-secondary :href="route('warranties.edit', $warranty)">
                Editar
            </x-button-secondary>

        </div>

    </div>




    {{-- Información general --}}

    <div class="bg-white rounded-lg border border-slate-200">

        <div class="px-6 py-4 border-b border-slate-200">

            <h3 class="font-semibold text-slate-800">
                Información general
            </h3>

        </div>


        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">


            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Código
                </p>

                <p class="font-medium text-slate-800 mt-1">
                    {{ $warranty->warranty_code }}
                </p>
            </div>



            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Proveedor
                </p>

                <p class="font-medium text-slate-800 mt-1">
                    {{ $warranty->provider }}
                </p>
            </div>




            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Estado
                </p>


              @php
    $today = now();

    $pending = $today->lt($warranty->start_date);
    $active = $today->between(
        $warranty->start_date,
        $warranty->end_date
    );
@endphp


@if($pending)

    <span class="inline-flex mt-1 rounded-md bg-amber-50 
    px-2 py-1 text-xs font-medium text-amber-700 border border-amber-200">

        Pendiente

    </span>


@elseif($active)

    <span class="inline-flex mt-1 rounded-md bg-emerald-50 
    px-2 py-1 text-xs font-medium text-emerald-700 border border-emerald-200">

        Activa

    </span>


@else

    <span class="inline-flex mt-1 rounded-md bg-red-50 
    px-2 py-1 text-xs font-medium text-red-700 border border-red-200">

        Vencida

    </span>

@endif


            </div>




            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Inicio
                </p>

                <p class="mt-1 text-slate-700">
                    {{ $warranty->start_date }}
                </p>

            </div>




            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Finalización
                </p>

                <p class="mt-1 text-slate-700">
                    {{ $warranty->end_date }}
                </p>

            </div>



            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Registrada
                </p>

                <p class="mt-1 text-slate-700">
                    {{ $warranty->created_at->format('d/m/Y') }}
                </p>

            </div>



        </div>

    </div>


<div class="bg-white rounded-lg border border-slate-200">

    <div class="px-6 py-4 border-b border-slate-200">

        <h3 class="font-semibold text-slate-800">
            Documento de garantía
        </h3>

    </div>


    <div class="p-6">

        @if($warranty->document_path)

            <a href="{{ asset('storage/'.$warranty->document_path) }}"
               target="_blank"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">

                Ver PDF de garantía

            </a>

        @else

            <p class="text-sm text-slate-400 italic">
                No existe documento adjunto.
            </p>

        @endif

    </div>

</div>


    





    {{-- Equipos asociados --}}

    <div class="bg-white rounded-lg border border-slate-200">


        <div class="px-6 py-4 border-b border-slate-200 flex justify-between">


            <h3 class="font-semibold text-slate-800">
                Equipos asociados
            </h3>


            <span class="rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">

                {{ $warranty->computers->count() }}
                equipos

            </span>


        </div>




        <div class="overflow-x-auto">


            <table class="min-w-full text-sm">

<thead class="bg-slate-50 border-b border-slate-200">

    <tr>

        <th class="px-6 py-3 text-left font-medium text-slate-500">
            Marca - Modelo
        </th>

        <th class="px-6 py-3 text-left font-medium text-slate-500">
            Serial
        </th>

        <th class="px-6 py-3 text-left font-medium text-slate-500">
            Activo fijo
        </th>

        <th class="px-6 py-3 text-left font-medium text-slate-500">
            Estado
        </th>

        <th class="px-6 py-3 text-right font-medium text-slate-500">
            Acción
        </th>

    </tr>

</thead>


                <tbody class="divide-y divide-slate-100">


                @forelse($warranty->computers as $computer)


                    <tr class="hover:bg-slate-50">


                        <td class="px-6 py-3 font-medium text-slate-800">

                            {{ $computer->brandModel->brand->name }} - {{ $computer->brandModel->name  }}

                        </td>



                        <td class="px-6 py-3 text-slate-600">

                            {{ $computer->serial }}

                        </td>




                        <td class="px-6 py-3 text-slate-600">

                            {{ $computer->fixed_asset ?? '-' }}

                        </td>




                        <td class="px-6 py-3">

    @switch($computer->status)

        @case('assigned')
            <span class="rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 border border-emerald-200">
                Asignado
            </span>
            @break

        @case('stock')
            <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 border border-slate-200">
                En stock
            </span>
            @break

        @case('faulty')
            <span class="rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 border border-red-200">
                Averiado
            </span>
            @break

        @case('obsolete')
            <span class="rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 border border-amber-200">
                Obsoleto
            </span>
            @break

        @default
            <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-500 border border-slate-200">
                —
            </span>

    @endswitch

</td>

<td class="px-6 py-3 text-right">

    <a href="{{ route('computers.show', $computer) }}"
       class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition">

        Ver equipo

        <svg xmlns="http://www.w3.org/2000/svg"
             fill="none"
             viewBox="0 0 24 24"
             stroke-width="2"
             stroke="currentColor"
             class="w-3.5 h-3.5">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />

        </svg>

    </a>

</td>

                    </tr>


                @empty


                    <tr>

                        <td colspan="4"
                            class="px-6 py-8 text-center text-slate-400">

                            No hay equipos asociados.

                        </td>

                    </tr>


                @endforelse


                </tbody>


            </table>


        </div>


    </div>

{{-- Notas --}}

    <div class="bg-white rounded-lg border border-slate-200">

        <div class="px-6 py-4 border-b border-slate-200">

            <h3 class="font-semibold text-slate-800">
                Notas
            </h3>

        </div>


        <div class="p-6 text-sm text-slate-600">

            {{ $warranty->notes ?: 'Sin notas registradas.' }}

        </div>

    </div>


</div>



@endsection