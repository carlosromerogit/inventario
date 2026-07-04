@extends('layouts.app')

@section('title', 'Detalle del usuario')
@section('header', 'Usuario: ' . $user->name)

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <a href="{{ route('users.index') }}"
           class="text-sm font-medium text-slate-600 hover:text-slate-900">
            ← Volver al listado
        </a>

        <div class="flex gap-2">

            <a href="{{ route('users.edit', $user) }}"
               class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                Editar usuario
            </a>

        </div>

    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">

        <h2 class="text-sm font-semibold text-slate-700 mb-6">
            Información del usuario
        </h2>

        <dl class="grid grid-cols-2 gap-6">

            <div>
                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                    Nombre
                </dt>

                <dd class="mt-1 text-sm text-slate-900 font-medium">
                    {{ $user->name }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                    Usuario
                </dt>

                <dd class="mt-1 text-sm font-mono text-slate-900">
                    {{ $user->username }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                    Correo electrónico
                </dt>

                <dd class="mt-1 text-sm text-slate-900">
                    {{ $user->email }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                    Rol
                </dt>

                <dd class="mt-1">

                    @foreach($user->roles as $role)

                        @php
                            $color = match($role->name){
                                'super_admin' => 'bg-red-100 text-red-800',
                                'admin' => 'bg-blue-100 text-blue-800',
                                default => 'bg-slate-100 text-slate-700'
                            };
                        @endphp

                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $color }}">
                            {{ ucwords(str_replace('_',' ', $role->name)) }}
                        </span>

                    @endforeach

                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                    Email verificado
                </dt>

                <dd class="mt-1">

                    @if($user->email_verified_at)

                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                            Sí
                        </span>

                    @else

                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                            No
                        </span>

                    @endif

                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                    Creado
                </dt>

                <dd class="mt-1 text-sm text-slate-900">
                    {{ $user->created_at->format('d/m/Y H:i') }}
                </dd>
            </div>

            <div>
                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                    Última actualización
                </dt>

                <dd class="mt-1 text-sm text-slate-900">
                    {{ $user->updated_at->format('d/m/Y H:i') }}
                </dd>
            </div>

        </dl>

    </div>

</div>

@endsection