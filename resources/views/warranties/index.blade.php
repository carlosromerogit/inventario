@extends('layouts.app')

@section('title', 'Garantías')
@section('header', 'Garantías')

@section('content')

<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">

    <form action="{{ route('warranties.index') }}" method="GET"
          class="flex flex-col sm:flex-row items-end gap-4">

        <div class="flex-1 w-full">

            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                Buscar garantía
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                autocomplete="off"
                placeholder="Código o proveedor..."
                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
            >

        </div>


        <div class="flex gap-2 w-full sm:w-auto">

            @if(request()->filled('search'))

                <a href="{{ route('warranties.index') }}"
                   class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
                    Limpiar
                </a>

            @endif


            <button
                class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Buscar
            </button>

        </div>

    </form>

</div>



<div class="flex items-center justify-between mb-6">

    <p class="text-sm text-slate-500">
        {{ $warranties->total() }} garantías encontradas
    </p>


    <x-button-secondary
        :href="route('warranties.create')"
        class="!bg-indigo-600 !text-white !ring-0 hover:!bg-indigo-500">

        + Nueva garantía

    </x-button-secondary>


</div>




<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">

<table class="min-w-full divide-y divide-slate-200">

<thead class="bg-slate-50">

<tr>

<th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
Código
</th>

<th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
Proveedor
</th>

<th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
Vigencia
</th>


<th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
Equipos
</th>


<th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
Estado
</th>


<th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
Acciones
</th>


</tr>

</thead>




<tbody class="divide-y divide-slate-100">


@forelse($warranties as $warranty)


<tr class="hover:bg-slate-50">


<td class="px-6 py-4 text-sm">

<a href="{{ route('warranties.show',$warranty) }}"
   class="font-medium text-slate-800 hover:text-indigo-600">

{{ $warranty->warranty_code }}

</a>

</td>




<td class="px-6 py-4 text-sm text-slate-700">

{{ $warranty->provider }}

</td>





<td class="px-6 py-4 text-sm text-slate-600">

{{ $warranty->start_date }}
<br>
<span class="text-xs text-slate-400">
hasta {{ $warranty->end_date }}
</span>

</td>





<td class="px-6 py-4 text-sm">

<span class="rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">

{{ $warranty->computers->count() }} equipos

</span>

</td>





<td class="px-6 py-4 text-sm">

@if(now()->lt($warranty->start_date))

<span class="rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 border border-amber-200">
Pendiente
</span>

@elseif(now()->between($warranty->start_date, $warranty->end_date))

<span class="rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 border border-emerald-200">
Activa
</span>

@else

<span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">
Vencida
</span>

@endif


</td>





<td class="px-6 py-4 text-right text-sm whitespace-nowrap">


<a href="{{ route('warranties.show',$warranty) }}"
   class="text-indigo-600 hover:text-indigo-800">
Ver
</a>


<a href="{{ route('warranties.edit',$warranty) }}"
   class="text-indigo-600 hover:text-indigo-800 ml-3">
Editar
</a>



<form action="{{ route('warranties.destroy',$warranty) }}"
      method="POST"
      class="inline ml-3"
      onsubmit="return confirm('¿Eliminar esta garantía?');">

@csrf
@method('DELETE')


<button class="text-red-600 hover:text-red-800">

Eliminar

</button>


</form>


</td>


</tr>


@empty


<tr>

<td colspan="6"
    class="px-6 py-10 text-center text-sm text-slate-400">

No existen garantías registradas.

</td>

</tr>


@endforelse


</tbody>

</table>

</div>




<div class="mt-4">

{{ $warranties->links() }}

</div>


@endsection