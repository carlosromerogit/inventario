<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse — Inventario TI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> {{-- CDN de respaldo --}}
</head>
<body class="h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto w-full max-w-md">
        <div class="text-center">
            <span class="text-4xl">🛠️</span>
            <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-slate-900">
                Crear nueva cuenta
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Regístrate para gestionar los activos de la empresa
            </p>
        </div>

        <div class="mt-8 bg-white py-8 px-4 shadow-sm border border-slate-200 sm:rounded-lg sm:px-10">
            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">
                        Nombre completo
                    </label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" required autofocus
                               value="{{ old('name') }}"
                               class="block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">
                        Correo electrónico
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required 
                               value="{{ old('email') }}"
                               class="block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">
                        Contraseña
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                               class="block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">
                        Confirmar contraseña
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                        Registrar cuenta
                    </button>
                </div>
            </form>

            <div class="mt-6 border-t border-slate-100 pt-4 text-center">
                <p class="text-sm text-slate-600">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>