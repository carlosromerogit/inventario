<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Inventario TI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto w-full max-w-md">
        <div class="text-center">
            <span class="text-4xl select-none">💻</span>
            <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-slate-900">
                Inventario de Activos TI
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Ingresa tus credenciales para acceder al panel
            </p>
        </div>

        <div class="mt-8 bg-white py-8 px-4 shadow-sm border border-slate-200 sm:rounded-lg sm:px-10">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700 mb-1">
                        Nombre de usuario
                    </label>
                    <div class="mt-1">
                        <input id="username" 
                               name="username" 
                               type="text" 
                               required 
                               autocomplete="off" 
                               autofocus
                               value="{{ old('username') }}"
                               class="block w-full rounded-md border text-sm px-3 py-2 text-slate-800 placeholder-slate-400 shadow-xs transition duration-150 ease-in-out focus:ring-1 focus:outline-hidden @error('username') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                    </div>
                    @error('username')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                        Contraseña
                    </label>
                    <div class="mt-1">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="off" 
                               required
                               class="block w-full rounded-md border text-sm px-3 py-2 text-slate-800 placeholder-slate-400 shadow-xs transition duration-150 ease-in-out focus:ring-1 focus:outline-hidden @error('password') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        <label for="remember" class="ml-2 block text-sm text-slate-600 select-none cursor-pointer">
                            Recordarme en este equipo
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="flex w-full justify-center rounded-md bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 transition cursor-pointer">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>