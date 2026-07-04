@extends('layouts.app')

@section('title', 'Nuevo usuario')
@section('header', 'Nuevo usuario')

@section('content')

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="space-y-6">

        <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-xs">

            <h2 class="text-sm font-semibold text-slate-700 mb-5">
                Datos del usuario
            </h2>

            <div class="grid grid-cols-2 gap-5">

                <x-input
                    name="name"
                    label="Nombre completo"
                    :value="old('name')"
                    required
                />

                <x-input
                    name="username"
                    label="Usuario"
                    :value="old('username')"
                    required
                />

                <x-input
                    type="email"
                    name="email"
                    label="Correo electrónico"
                    :value="old('email')"
                    required
                />

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Rol
                    </label>

                    <select
                        name="role"
                        required
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                        <option value="">Seleccione...</option>

                        @foreach($roles as $role)
                            <option
                                value="{{ $role->name }}"
                                {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ', $role->name)) }}
                            </option>
                        @endforeach

                    </select>

                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <x-input
                    type="password"
                    name="password"
                    label="Contraseña"
                    required
                />

                <x-input
                    type="password"
                    name="password_confirmation"
                    label="Confirmar contraseña"
                    required
                />

            </div>

        </div>

        <div class="flex gap-3">

            <button
                class="rounded-md bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-500">
                Guardar usuario
            </button>

            <a
                href="{{ route('users.index') }}"
                class="rounded-md border border-slate-300 px-4 py-2 text-slate-700 hover:bg-slate-100">
                Cancelar
            </a>

        </div>

    </div>

</form>

@endsection