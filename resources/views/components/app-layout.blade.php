<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cuantica Group</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/291c56a30a.js" crossorigin="anonymous"></script>
   @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="bg-gray-50">
    
    <div class="flex min-h-screen">
        <!-- Sidebarr -->
        @include('components.sidebar')       
        <div class="flex-1 flex flex-col">         
            <main class="flex-1 p-6">
                <!-- contenid variable -->
                {{ $slot ?? '' }}        
            </main>
            <footer class="py-6 text-center text-sm text-gray-500">
                Cuantica Group. Todos los derechos reservados.
            </footer>
        </div>
    </div>
</body>
</html>