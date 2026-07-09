@extends('layouts.app')

@section('title', 'Usuarios')
@section('header', 'Usuarios')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div class="text-sm text-slate-500">
            Total: {{ $users->total() }} usuarios
        </div>

        @can('users.create')
            <a href="{{ route('users.create') }}"
               class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                + Crear usuario
            </a>
        @endcan

    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">
                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                        Nombre
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                        Usuario
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                        Email
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                        Rol
                    </th>

                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">
                        Acciones
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 bg-white">

                @forelse($users as $user)

                    <tr class="hover:bg-slate-50">

                        <td class="px-6 py-4 text-sm font-medium text-slate-900">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4 text-sm font-mono text-slate-700">
                            {{ $user->username }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-4 text-sm">

                            @foreach($user->roles as $role)

                                @php
                                    $color = match($role->name){
                                        'super_admin' => 'bg-red-100 text-red-800',
                                        'admin' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-slate-100 text-slate-700'
                                    };
                                @endphp

                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $color }}">
                                    {{ ucwords(str_replace('_',' ', $role->name)) }}
                                </span>

                            @endforeach

                        </td>


                        <td class="px-6 py-4 text-right text-sm space-x-3">

                            <a href="{{ route('users.show', $user) }}"
                               class="text-slate-600 hover:text-slate-900 font-medium">
                                Ver
                            </a>

                            @can('users.edit')
                                <a href="{{ route('users.edit', $user) }}"
                                   class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    Editar
                                </a>
                            @endcan

                            @can('users.delete')
                                <form action="{{ route('users.destroy', $user) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('¿Eliminar este usuario?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600 hover:text-red-900 font-medium">
                                        Eliminar
                                    </button>

                                </form>
                            @endcan

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                            No hay usuarios registrados
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div>
        {{ $users->links() }}
    </div>

</div>

@endsection