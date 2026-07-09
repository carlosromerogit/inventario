@extends('layouts.app')

@section('title', 'Página no encontrada')

@section('content')
<div class="flex flex-col items-center justify-center py-12 text-center">
    {{-- Un ícono o un texto grande --}}
    <h1 class="text-7xl font-bold text-slate-300 mb-2">404</h1>
    
    <h2 class="text-xl font-semibold text-slate-700 mb-4">
        ¡Ups! La página que buscas no existe
    </h2>
    
    <p class="text-slate-500 max-w-md mb-8 text-sm">
        Es posible que el enlace esté roto, que se haya eliminado la página o que hayas escrito mal la URL. ¡Verifica e intenta de nuevo!
    </p>

    {{-- Botón para regresar a un lugar seguro --}}
    <a href="{{ route('computers.index') }}" 
       class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 transition">
        Regresar al inicio
    </a>
</div>
@endsection