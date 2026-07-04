<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventario') · InvTrack</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> {{-- CDN de soporte en desarrollo --}}
</head>
<body class="h-full font-sans antialiased text-slate-800">
    <div class="flex h-full min-h-screen">

        <aside class="w-64 shrink-0 bg-slate-900 text-slate-300 flex flex-col border-r border-slate-950">
            <div class="px-6 py-5 border-b border-slate-800">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-md bg-indigo-500 text-white font-bold text-sm">IT</span>
                    <span class="text-white font-semibold tracking-tight">InvTrack</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">

    <div>
        <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">General</p>
        <x-nav-link :route="'dashboard'" :active="request()->routeIs('dashboard')">
            Panel de Control
        </x-nav-link>
    </div>

    <div>
        <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Inventario</p>
        <x-nav-link :route="'computers.index'" :active="request()->routeIs('computers.*')">
            Computadoras
        </x-nav-link>
    </div>

    <div>
        <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Directorio</p>

        <x-nav-link :route="'employees.index'" :active="request()->routeIs('employees.*')">
            Empleados
        </x-nav-link>

        <x-nav-link :route="'departments.index'" :active="request()->routeIs('departments.*')">
            Departamentos
        </x-nav-link>

        <x-nav-link :route="'companies.index'" :active="request()->routeIs('companies.*')">
            Empresas
        </x-nav-link>
    </div>

    <div>
        <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Catálogos</p>

        <x-nav-link :route="'brands.index'" :active="request()->routeIs('brands.*')">
            Marcas
        </x-nav-link>

        <x-nav-link :route="'brand-models.index'" :active="request()->routeIs('brand-models.*')">
            Modelos
        </x-nav-link>

        <x-nav-link :route="'drive-types.index'" :active="request()->routeIs('drive-types.*')">
            Tipos de disco
        </x-nav-link>

        <x-nav-link :route="'operating-systems.index'" :active="request()->routeIs('operating-systems.*')">
            Sistemas operativos
        </x-nav-link>
    </div>
@can('users.index')
<div>
    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">
        Administrador
    </p>

    <x-nav-link :route="'users.index'" :active="request()->routeIs('users.*')">
        Usuarios
    </x-nav-link>
</div>
@endcan

</nav>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
            
            <header class="bg-white border-b border-slate-200 px-8 py-4 shrink-0 flex items-center justify-between shadow-xs">
                <div>
                    <h1 class="text-lg font-semibold text-slate-900">@yield('header', 'Inventario')</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 font-mono">@<span>{{ Auth::user()->username }}</span></p>
                    </div>
                    
                    <div class="h-6 w-px bg-slate-200"></div>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center gap-1.5 rounded-md bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-red-600 shadow-xs transition cursor-pointer">
                            <span>Cerrar sesión</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            <main class="px-8 py-6">
                <div class="w-full">
                    
                    @if (session('success'))
                        <div class="mb-6 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span>✅</span>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                            <p class="font-medium mb-1 flex items-center gap-2">
                                <span>⚠️</span>
                                <span>Revisa los siguientes errores:</span>
                            </p>
                            <ul class="list-disc list-inside space-y-0.5 ml-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

        </div>
    </div>
</body>
</html>