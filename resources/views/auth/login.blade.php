<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cuantica Group</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>

<body>

    <header></header>


    <div class="flex min-h-screen bg-gray-50">
        <!-- Lado Izquierdo -->
        <div class="flex w-full md:w-1/2 items-center justify-center p-8">
            <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-sm">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <img src="/images/logo.png" alt="Logo" class="h-12">
                </div>

                <!-- Título -->
                <h1 class="text-2xl font-semibold text-gray-800 mb-2 text-center">Inicia sesión</h1>
                <p class="text-gray-500 text-center mb-8 text-sm">
                    Bienvenido, usa tus credenciales para acceder
                </p>
                <!-- Mensaje de éxito -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif
                <!-- Mensaje de error de credenciales -->
                @error('email')
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
                    </div>
                @enderror
                <!-- Formulario -->
                <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <!-- Icono Mail -->

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>

                        </span>
                        <input type="email" name="email" placeholder="Correo electrónico"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-[#60A5FA] focus:outline-none" />
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <!-- Icono Lock -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>

                        </span>
                        <input type="password" name="password" placeholder="Contraseña"
                            class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-[#60A5FA] focus:outline-none" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 cursor-pointer">
                            <!-- Icono Eye -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </span>
                    </div>

                    <!-- Forgot Password -->
                    <div class="text-right">
                        <a href="#" class="text-sm text-[#FF9C00] hover:underline">¿Olvidaste tu contraseña?</a>
                    </div>

                    <!-- Botón principal -->
                    <button type="submit"
                        class="w-full bg-[#FF9C00] cursor-pointer hover:bg-[#ffb733] text-white font-medium py-2.5 rounded-lg text-sm shadow-sm transition">
                        Iniciar sesión
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

                <!-- Sign Up -->
                <p class="text-center text-xs mt-8 text-gray-500">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" class="text-[#FF9C00] hover:underline font-medium">Regístrate</a>
                </p>
            </div>
        </div>

        <!-- Lado Derecho -->
        <div
            class="hidden md:flex w-1/2 relative items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 via-orange-50 to-white">
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
                    Cuantica Group Forum
                </h2>
                <p class="text-gray-600 mt-3 text-base">
                    Entorno desarrollado para tareas, horarios y asistencias de tu equipo de manera sencilla y
                    eficiente.
                </p>
            </div>
        </div>

    </div>

    <footer></footer>

</body>

</html>
