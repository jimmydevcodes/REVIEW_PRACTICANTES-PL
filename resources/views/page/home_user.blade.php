@php
use App\Models\Tarea;
use App\Models\Proyecto;
use App\Models\Participante;

$participante = Participante::where('user_id', Auth::id())->first();

// Tareas pendientes
$tareasPendientes = Tarea::where('participante_id', $participante->id_participante)
    ->whereIn('estado', ['pendiente', 'ausente'])
    ->count();

// Proyectos activos
$proyectosActivos = $participante->proyectos()
    ->where('Estado', 'activo')
    ->count();

// Notificaciones nuevas (valor temporal)
$notificacionesNuevas = 0;

// Actividades recientes (últimas tareas modificadas)
$actividadesRecientes = Tarea::where('participante_id', $participante->id_participante)
    ->orderBy('ultima_actividad', 'desc')
    ->with('proyecto') // Eager load para evitar N+1
    ->take(5)
    ->get()
    ->map(function($tarea) {
        return [
            'tipo' => $tarea->estado === 'completado' ? 'completada' : 'nueva',
            'proyecto' => $tarea->proyecto ? $tarea->proyecto->nombre_proyecto : 'Sin proyecto',
            'fecha' => $tarea->ultima_actividad ?: now() // Si es null, usar fecha actual
        ];
    });

// Calculamos el porcentaje de asistencia basado en las tareas del mes actual
$inicioMes = now()->startOfMonth();
$finMes = now()->endOfMonth();
$tareasDelMes = Tarea::where('participante_id', $participante->id_participante)
    ->whereBetween('fecha_inicio', [$inicioMes, $finMes])
    ->count();

$tareasCompletadasDelMes = Tarea::where('participante_id', $participante->id_participante)
    ->whereBetween('fecha_inicio', [$inicioMes, $finMes])
    ->where('estado', 'completado')
    ->count();

$asistenciaPorcentaje = $tareasDelMes > 0 
    ? round(($tareasCompletadasDelMes / $tareasDelMes) * 100) 
    : 100;

// Eventos (esto se mantiene estático por ahora ya que no hay tabla de eventos)
$eventos = [
    'scrum' => [
        'title' => 'Capacitación Metodología Scrum',
        'date' => 'Lunes, 30 de Septiembre',
        'time' => '2:00 PM - 3:00 PM',
        'location' => 'Sala de Reuniones Virtual',
        'description' => 'Capacitación sobre metodología ágil Scrum y sus beneficios en el desarrollo de proyectos.',
        'participants' => ['Equipo de Desarrollo', 'Equipo de Soporte', 'Product Owners'],
        'agenda' => ['Introducción a Scrum', 'Roles y Responsabilidades', 'Eventos Scrum', 'Artefactos', 'Práctica']
    ],
    'vuejs' => [
        'title' => 'Capacitación Vue.js',
        'date' => 'Miércoles, 2 de Octubre',
        'time' => '8:00 PM - 9:00 PM',
        'location' => 'Sala de Capacitación Online',
        'description' => 'Aprende los fundamentos de Vue.js y cómo implementarlo en proyectos web modernos.',
        'participants' => ['Desarrolladores Frontend', 'Desarrolladores Full Stack'],
        'agenda' => ['Conceptos Básicos', 'Componentes', 'Manejo de Estado', 'Vue Router', 'Vuex']
    ],
    'laravel' => [
        'title' => 'Capacitación Laravel Avanzado',
        'date' => 'Viernes, 4 de Octubre',
        'time' => '2:00 PM - 3:00 PM',
        'location' => 'Plataforma Zoom',
        'description' => 'Profundiza tus conocimientos en Laravel con temas avanzados y mejores prácticas.',
        'participants' => ['Desarrolladores Backend', 'Desarrolladores Full Stack'],
        'agenda' => ['Eloquent Avanzado', 'Testing', 'API REST', 'Redis y Cache', 'Deployment']
    ]
];
@endphp

<x-app-layout>
    <x-slot:title>Dashboard</x-slot:title>
    
    <!-- Bienvenida personalizada -->
    <div class="mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
            Bienvenido, {{ Auth::user()->name }}
        </h2>
        <p class="text-gray-600 mt-1 text-sm md:text-base">
            {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
        </p>
    </div>

    <!-- Carrusel de Avisos -->
    <div class="relative mb-8 overflow-hidden rounded-xl bg-white shadow-sm">
        <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
            <!-- Slide 1 -->
            <div class="min-w-full relative">
                <img src="/images/avisos/capacitacion_scrum.jpg" alt="Reunión de equipo" 
                    class="w-full h-48 md:h-64 object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                    <div class="p-6 text-white w-full">
                        <h3 class="text-xl md:text-2xl font-bold mb-2">Capacitación Metodología Scrum y Webinar</h3>
                        <p class="text-sm md:text-base">Inicia próximo Lunes 2:00pm - 3:00pm - Todos los equipos</p>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="min-w-full relative">
                <img src="/images/avisos/vuejs.jpg" alt="Nuevo proyecto" 
                    class="w-full h-48 md:h-64 object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                    <div class="p-6 text-white w-full">
                        <h3 class="text-xl md:text-2xl font-bold mb-2">Capacitación Vue.js</h3>
                        <p class="text-sm md:text-base">Inicia próximo Miércoles 8:00pm - 9:00pm - Todos los equipos</p>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="min-w-full relative">
                <img src="/images/avisos/capacitacion_laravel.jpg" alt="Capacitación" 
                    class="w-full h-48 md:h-64 object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                    <div class="p-6 text-white w-full">
                        <h3 class="text-xl md:text-2xl font-bold mb-2">Capacitación Laravel Avanzado</h3>
                        <p class="text-sm md:text-base">Inicia próximo Viernes 2:00pm - 3:00pm - Modalidad Virtual</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controles del carrusel -->
        <button onclick="prevSlide()" 
            class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow-lg transition">
            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button onclick="nextSlide()" 
            class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow-lg transition">
            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Indicadores -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
            <button onclick="goToSlide(0)" class="carousel-dot w-2 h-2 rounded-full bg-white transition"></button>
            <button onclick="goToSlide(1)" class="carousel-dot w-2 h-2 rounded-full bg-white/50 transition"></button>
            <button onclick="goToSlide(2)" class="carousel-dot w-2 h-2 rounded-full bg-white/50 transition"></button>
        </div>
    </div>

    <!-- Resumen de actividad -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs md:text-sm text-gray-600">Tareas</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $tareasPendientes }}</p>
                </div>
                <div class="bg-orange-100 p-2 md:p-3 rounded-lg">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#FF9C00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs md:text-sm text-gray-600">Proyectos activos</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $proyectosActivos }}</p>
                </div>
                <div class="bg-blue-100 p-2 md:p-3 rounded-lg">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs md:text-sm text-gray-600">Asistencia mes</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $asistenciaPorcentaje }}%</p>
                </div>
                <div class="bg-green-100 p-2 md:p-3 rounded-lg">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs md:text-sm text-gray-600">Mensajes nuevos</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $notificacionesNuevas }}</p>
                </div>
                <div class="bg-purple-100 p-2 md:p-3 rounded-lg">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad reciente y próximos eventos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Actividad reciente -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actividad Reciente</h3>
            <div class="space-y-4">
                @foreach($actividadesRecientes as $actividad)
                <div class="flex items-start gap-3">
                    <div class="bg-{{ $actividad['tipo'] == 'completada' ? 'green' : 'orange' }}-100 p-2 rounded-lg flex-shrink-0">
                        @if($actividad['tipo'] == 'completada')
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-[#FF9C00]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $actividad['tipo'] == 'completada' ? 'Tarea completada' : 'Nueva tarea asignada' }}</p>
                        <p class="text-xs text-gray-600">Proyecto "{{ $actividad['proyecto'] }}" - {{ optional($actividad['fecha'])->diffForHumans() ?: 'Hace un momento' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Próximos eventos -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Próximos Eventos</h3>
            <div class="space-y-4">
                <div class="border-l-4 border-[#FF9C00] pl-4 cursor-pointer hover:bg-orange-50 -ml-2 p-2 rounded-r transition"
                    onclick="openEventModal('scrum')">
                    <p class="text-sm font-medium text-gray-800">Capacitación Metodología Scrum</p>
                    <p class="text-xs text-gray-600">Lunes, 2:00 PM - 3:00 PM</p>
                </div>

                <div class="border-l-4 border-blue-500 pl-4 cursor-pointer hover:bg-blue-50 -ml-2 p-2 rounded-r transition"
                    onclick="openEventModal('vuejs')">
                    <p class="text-sm font-medium text-gray-800">Capacitación Vue.js</p>
                    <p class="text-xs text-gray-600">Miércoles, 8:00 PM - 9:00 PM</p>
                </div>

                <div class="border-l-4 border-green-500 pl-4 cursor-pointer hover:bg-green-50 -ml-2 p-2 rounded-r transition"
                    onclick="openEventModal('laravel')">
                    <p class="text-sm font-medium text-gray-800">Capacitación Laravel Avanzado</p>
                    <p class="text-xs text-gray-600">Viernes, 2:00 PM - 3:00 PM</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de evento -->
    <div id="eventModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-lg w-full p-6 relative">
            <button onclick="closeEventModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div id="modalContent"></div>
        </div>
    </div>

    <script>
        // Carrusel
        let currentSlide = 0;
        const slides = document.querySelectorAll('#carousel > div');
        const dots = document.querySelectorAll('.carousel-dot');

        function updateCarousel() {
            document.getElementById('carousel').style.transform = `translateX(-${currentSlide * 100}%)`;
            dots.forEach((dot, index) => {
                dot.classList.toggle('bg-white', index === currentSlide);
                dot.classList.toggle('bg-white/50', index !== currentSlide);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            updateCarousel();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateCarousel();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
        }

        // Auto-play
        setInterval(nextSlide, 5000);

        // Modal de eventos
        const events = @json($eventos ?? []);

        function openEventModal(eventKey) {
            const event = events[eventKey];
            if (!event) return;
            
            const modal = document.getElementById('eventModal');
            const content = document.getElementById('modalContent');

            content.innerHTML = `
                <h2 class="text-2xl font-bold text-gray-800 mb-4">${event.title}</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#FF9C00] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800">${event.date}</p>
                            <p class="text-sm text-gray-600">${event.time}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#FF9C00] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-gray-800">${event.location}</p>
                    </div>

                    <div>
                        <p class="text-gray-700">${event.description}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Participantes:</h3>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            ${event.participants.map(p => `<li>${p}</li>`).join('')}
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Agenda:</h3>
                        <ol class="list-decimal list-inside text-sm text-gray-600 space-y-1">
                            ${event.agenda.map(a => `<li>${a}</li>`).join('')}
                        </ol>
                    </div>

                    <button onclick="closeEventModal()" 
                        class="w-full bg-[#FF9C00] hover:bg-[#ffb733] text-white font-medium py-2 rounded-lg transition mt-4">
                        Cerrar
                    </button>
                </div>
            `;

            modal.classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) closeEventModal();
        });
    </script>
</x-app-layout>