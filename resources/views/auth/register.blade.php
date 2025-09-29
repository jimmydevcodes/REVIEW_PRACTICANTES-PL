<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Registro</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <div class="flex min-h-screen bg-gray-50">
        <!-- Lado Izquierdo -->
        <div class="flex w-full md:w-1/2 items-center justify-center p-8">
            <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-sm">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <img src="/images/logo.png" alt="Logo" class="h-12">
                </div>

                <!-- Título -->
                <h1 class="text-2xl font-semibold text-gray-800 mb-2 text-center">Registro de Usuario</h1>
                <p class="text-gray-500 text-center mb-8 text-sm">
                    Complete el formulario para crear su cuenta
                </p>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="font-medium text-red-600 text-sm mb-2">
                            {{ __('Whoops! Something went wrong.') }}
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulario -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name (Nombre completo en un solo campo) -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </span>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="Nombre completo"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-[#60A5FA] focus:outline-none" />
                        @error('name')
                            <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="Correo electrónico"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-[#60A5FA] focus:outline-none" />
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" required
                            placeholder="Contraseña"
                            class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-[#60A5FA] focus:outline-none" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </span>
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Confirmar contraseña"
                            class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-[#60A5FA] focus:outline-none" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </span>
                    </div>

                    <!-- Botón principal -->
                    <button type="submit"
                        class="w-full bg-[#FF9C00] cursor-pointer hover:bg-[#ffb733] text-white font-medium py-2.5 rounded-lg text-sm shadow-sm transition">
                        Registrarse
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center my-8">
                    <hr class="flex-grow border-gray-300">
                    <span class="px-3 text-gray-400 text-sm">o continúa con</span>
                    <hr class="flex-grow border-gray-300">
                </div>

                <!-- Social Login -->
                <div class="flex gap-3">
                    <button
                        class="flex-1 flex items-center justify-center gap-2 border border-gray-200 rounded-lg py-2 text-sm hover:bg-gray-50 transition">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-4 h-4"> Google
                    </button>
                    <button
                        class="flex-1 flex items-center justify-center gap-2 border border-gray-200 rounded-lg py-2 text-sm hover:bg-gray-50 transition">
                        <img src="https://www.svgrepo.com/show/452196/facebook-1.svg" class="w-4 h-4"> Facebook
                    </button>
                </div>

                <!-- Sign In -->
                <p class="text-center text-xs mt-8 text-gray-500">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="text-[#FF9C00] hover:underline font-medium">Iniciar Sesión</a>
                </p>
            </div>
        </div>

        <!-- Lado Derecho -->
        <div class="hidden md:flex w-1/2 relative items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 via-orange-50 to-white">
            <!-- Imagen del dashboard borrosa -->
            <img src="https://www.cuanticagroup.com/images/_MG_6104.jpg" alt="Dashboard Mockup"
                class="absolute inset-0 w-full h-full object-cover blur-sm opacity-40">

            <!-- Overlay degradado -->
            <div class="absolute inset-0 bg-gradient-to-br from-white/70 to-orange-100/40"></div>

            <!-- Pattern geométrico (grid) -->
            <svg class="absolute inset-0 w-full h-full opacity-10 text-gray-400" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>

            <!-- Texto -->
            <div class="relative z-10 text-center px-8 max-w-md">
                <h2 class="text-gray-800 text-3xl font-bold tracking-tight">
                    Únete a Cuantica Group
                </h2>
                <p class="text-gray-600 mt-3 text-base">
                    Crea tu cuenta y comienza a gestionar las tareas, horarios y asistencias de tu equipo de manera eficiente.
                </p>
            </div>
        </div>
    </div>
</body>
</html>