<x-app-layout>
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mis Tareas</h1>
                    <p class="text-gray-600 mt-1">Gestiona tus tareas por grupo de trabajo</p>
                </div>
                <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg shadow-sm border">
                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-sm text-gray-600">{{ $grupos->count() }} grupos activos</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i class="fas fa-tasks text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Grupos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $grupos->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tareas Pendientes</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $totalPendientes = 0;
                                foreach ($grupos as $grupo) {
                                    if ($grupo->proyecto && auth()->user()->participante) {
                                        $totalPendientes += $grupo->proyecto->tareas
                                            ->where('participante_id', auth()->user()->participante->id_participante)
                                            ->where('estado', 'pendiente')
                                            ->count();
                                    }
                                }
                            @endphp
                            {{ $totalPendientes }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completadas</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $totalCompletadas = 0;
                                foreach ($grupos as $grupo) {
                                    if ($grupo->proyecto && auth()->user()->participante) {
                                        $totalCompletadas += $grupo->proyecto->tareas
                                            ->where('participante_id', auth()->user()->participante->id_participante)
                                            ->where('estado', 'completado')
                                            ->count();
                                    }
                                }
                            @endphp
                            {{ $totalCompletadas }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Incompletas</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $totalIncompletas = 0;
                                foreach ($grupos as $grupo) {
                                    if ($grupo->proyecto && auth()->user()->participante) {
                                        $totalIncompletas += $grupo->proyecto->tareas
                                            ->where('participante_id', auth()->user()->participante->id_participante)
                                            ->where('estado', 'incompleto')
                                            ->count();
                                    }
                                }
                            @endphp
                            {{ $totalIncompletas }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Groups Grid -->
        @if($grupos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($grupos as $grupo)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 overflow-hidden group cursor-pointer"
                         onclick="window.location.href='{{ route('tasks.group', $grupo->id_grupo) }}'">
                        
                        <!-- Header Card -->
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold">{{ $grupo->nombre_grupo }}</h3>
                                    <p class="text-orange-100 text-sm mt-1">{{ $grupo->proyecto->nombre_proyecto ?? 'Sin proyecto' }}</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-lg p-2">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Descripción -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $grupo->descripcion ?? 'Sin descripción disponible' }}
                            </p>

                            <!-- Cliente Info -->
                            @if($grupo->proyecto && $grupo->proyecto->cliente)
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <i class="fas fa-building w-4 mr-2"></i>
                                    <span>{{ $grupo->proyecto->cliente->nombre_cliente }}</span>
                                </div>
                            @endif

                            <!-- Participantes -->
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Participantes ({{ $grupo->participantes->count() }})</p>
                                <div class="flex -space-x-2">
                                    @foreach($grupo->participantes->take(4) as $participante)
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($participante->nombre . ' ' . $participante->apellido) }}&background=FF9C00&color=fff"
                                             alt="{{ $participante->nombre }}"
                                             class="w-8 h-8 rounded-full border-2 border-white shadow-sm"
                                             title="{{ $participante->nombre }} {{ $participante->apellido }}">
                                    @endforeach
                                    
                                    @if($grupo->participantes->count() > 4)
                                        <div class="w-8 h-8 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs text-gray-600 font-medium">
                                            +{{ $grupo->participantes->count() - 4 }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Progress & Stats -->
                            <div class="space-y-3">
                                <!-- Tasks Stats -->
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Mis tareas:</span>
                                    <div class="flex space-x-4">
                                        @php
                                            $misTareas = $grupo->proyecto && auth()->user()->participante 
                                                ? $grupo->proyecto->tareas->where('participante_id', auth()->user()->participante->id_participante) 
                                                : collect();
                                            $pendientes = $misTareas->where('estado', 'pendiente')->count();
                                            $completadas = $misTareas->where('estado', 'completado')->count();
                                            $total = $misTareas->count();
                                        @endphp
                                        
                                        <span class="text-blue-600 font-medium">{{ $pendientes }} pendientes</span>
                                        <span class="text-green-600 font-medium">{{ $completadas }} completadas</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                @if($total > 0)
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-orange-400 to-orange-500 h-2 rounded-full transition-all duration-300"
                                             style="width: {{ ($completadas / $total) * 100 }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 text-center">{{ round(($completadas / $total) * 100) }}% completado</p>
                                @else
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gray-300 h-2 rounded-full"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 text-center">Sin tareas asignadas</p>
                                @endif
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    Creado: {{ $grupo->fecha_creacion ? \Carbon\Carbon::parse($grupo->fecha_creacion)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <div class="flex items-center text-orange-600 text-sm font-medium group-hover:text-orange-700 transition-colors">
                                    <span>Ver tareas</span>
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 max-w-md mx-auto">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tasks text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Sin grupos asignados</h3>
                    <p class="text-gray-600 mb-4">Aún no tienes grupos de trabajo asignados. Contacta con tu administrador.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
</x-app-layout>