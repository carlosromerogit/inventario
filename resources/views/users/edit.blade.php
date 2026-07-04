@extends('layouts.app')

@section('title', 'Editar usuario')
@section('header', 'Editar usuario')

@section('content')

<form action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="space-y-6">

        <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-xs">
            <h2 class="text-sm font-semibold text-slate-700 mb-5">
                Datos del usuario
            </h2>

            <div class="grid grid-cols-2 gap-5">

                {{-- Nombre --}}
                <x-input
                    name="name"
                    label="Nombre completo"
                    :value="old('name', $user->name)"
                    required
                />

                {{-- Usuario --}}
                <x-input
                    name="username"
                    label="Usuario"
                    :value="old('username', $user->username)"
                    required
                />

                {{-- Email --}}
                <x-input
                    name="email"
                    type="email"
                    label="Correo electrónico"
                    :value="old('email', $user->email)"
                    required
                />

                {{-- Rol --}}
                <x-select
                    name="role"
                    label="Rol"
                    :options="$roles->pluck('name','name')"
                    :selected="old('role', $user->roles->first()?->name)"
                    required
                />

                {{-- Password --}}
                <x-input
                    name="password"
                    type="password"
                    label="Nueva contraseña"
                />

                {{-- Confirmación --}}
                <x-input
                    name="password_confirmation"
                    type="password"
                    label="Confirmar nueva contraseña"
                />

            </div>

            <div class="mt-4 rounded-md bg-blue-50 border border-blue-200 p-3 text-sm text-blue-700">
                Deje los campos de contraseña vacíos si no desea cambiarla.
            </div>

        </div>

        <div class="flex gap-3">

            <button
                class="rounded-md bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-500">
                Guardar cambios
            </button>

            <a href="{{ route('users.index') }}"
               class="rounded-md border px-4 py-2 text-slate-700 hover:bg-slate-50">
                Cancelar
            </a>

        </div>

    </div>

</form>

@endsection