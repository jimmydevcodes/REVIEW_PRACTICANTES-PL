@php
use Illuminate\Support\Str;

function getColorForArea($areaName) {
    $colors = [
        'Desarrollo Software' => 'blue',
        'Marketing Digital' => 'orange',
        'Soporte' => 'green',
        'default' => 'gray'
    ];
    return $colors[$areaName] ?? $colors['default'];
}

function getIconForArea($areaName) {
    $icons = [
        'Desarrollo Software' => 'fa-code',
        'Marketing Digital' => 'fa-bullhorn',
        'Soporte' => 'fa-headset',
        'default' => 'fa-building'
    ];
    return $icons[$areaName] ?? $icons['default'];
}
@endphp
<x-app-layout>
    <x-slot:title>Mis Grupos</x-slot:title>

    <!-- Header -->
    <div class="mb-8 animate-fade-in">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
            Mis Grupos
        </h2>
        <p class="text-gray-600 mt-1 text-sm md:text-base">
            Grupos a los que perteneces y proyectos asignados
        </p>
    </div>

    <!-- Resumen por área -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
        @foreach($areas as $area)
        <div class="bg-gradient-to-br from-{{ $area->grupos_count > 0 ? getColorForArea($area->nombre_area) : 'gray' }}-50 to-{{ $area->grupos_count > 0 ? getColorForArea($area->nombre_area) : 'gray' }}-100 rounded-xl shadow-sm p-6 border-l-4 border-{{ $area->grupos_count > 0 ? getColorForArea($area->nombre_area) : 'gray' }}-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-{{ $area->grupos_count > 0 ? getColorForArea($area->nombre_area) : 'gray' }}-700 font-medium">{{ $area->nombre_area }}</p>
                    <p class="text-2xl font-bold text-{{ $area->grupos_count > 0 ? getColorForArea($area->nombre_area) : 'gray' }}-900 mt-1">{{ $area->grupos_count }} {{ Str::plural('Grupo', $area->grupos_count) }}</p>
                </div>
                <div class="bg-{{ $area->grupos_count > 0 ? getColorForArea($area->nombre_area) : 'gray' }}-200 p-3 rounded-lg">
                    <i class="fas {{ getIconForArea($area->nombre_area) }} text-{{ $area->grupos_count > 0 ? getColorForArea($area->nombre_area) : 'gray' }}-700 text-2xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Grupos del usuario -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800">Tus Grupos Activos</h3>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($groups as $group)
                <div class="group border-2 border-gray-200 rounded-xl p-6 hover:border-[#FF9C00] hover:shadow-lg transition-all duration-300 cursor-pointer"
                    onclick="openGroupModal({{ $group['id_grupo'] }})">
                    
                    <!-- Header del grupo -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-{{ $group['color'] }}-100 p-3 rounded-lg">
                                <i class="fas fa-users text-{{ $group['color'] }}-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-lg group-hover:text-[#FF9C00] transition-colors">{{ $group['nombre_grupo'] }}</h4>
                                <span class="inline-block px-3 py-1 bg-orange-100 text-[#FF9C00] text-xs font-medium rounded-full mt-1">
                                    {{ $group['area'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <p class="text-sm text-gray-600 mb-4">
                        {{ $group['descripcion'] }}
                    </p>

                    <!-- Proyecto asignado -->
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 border-l-4 border-[#FF9C00] p-4 rounded-r mb-4">
                        <p class="text-xs text-orange-700 font-medium mb-1 flex items-center gap-2">
                            <i class="fas fa-project-diagram"></i>
                            Proyecto Actual
                        </p>
                        <p class="font-semibold text-gray-800">{{ $group['project'] }}</p>
                    </div>

                    <!-- Info adicional -->
                    <div class="flex items-center justify-between text-sm border-t pt-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1 text-gray-600">
                                <i class="fas fa-user-friends"></i>
                                <span>{{ $group['members_count'] }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-gray-600">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ \Carbon\Carbon::parse($group['fecha_creacion'])->format('M Y') }}</span>
                            </div>
                        </div>
                        <button class="text-[#FF9C00] hover:text-orange-600 font-medium flex items-center gap-2 transition-colors">
                            Ver más
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal de grupo -->
    <div id="groupModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full p-6 relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeGroupModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>

            <div id="modalContent"></div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>

    <script>
        const groupsData = {!! json_encode($groups) !!};

        function openGroupModal(groupId) {
            const group = groupsData.find(g => g.id_grupo === groupId);
            if (!group) return;

            const modal = document.getElementById('groupModal');
            const content = document.getElementById('modalContent');

            const mockMembers = [
                { name: '{{ Auth::user()->name }}', role: 'Desarrollador', avatar: '{{ substr(Auth::user()->name, 0, 2) }}' },
                { name: 'María López', role: 'Frontend Developer', avatar: 'ML' },
                { name: 'Pedro Ruiz', role: 'Backend Developer', avatar: 'PR' },
                { name: 'Ana Torres', role: 'UI/UX Designer', avatar: 'AT' },
            ];

            content.innerHTML = `
                <div class="mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-orange-100 p-4 rounded-xl">
                            <i class="fas fa-users text-[#FF9C00] text-3xl"></i>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-800">${group.nombre_grupo}</h2>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="px-3 py-1 bg-orange-100 text-[#FF9C00] text-sm font-medium rounded-full">
                                    ${group.area}
                                </span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">
                                    <i class="fas fa-hashtag"></i> ${group.id_grupo}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-4">${group.descripcion || 'Sin descripción'}</p>

                    <!-- Información del grupo -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600 mb-1 flex items-center gap-2">
                                <i class="fas fa-key text-[#FF9C00]"></i>
                                Código de Acceso
                            </p>
                            <div class="flex items-center justify-between">
                                <code class="font-mono font-bold text-[#FF9C00]">${group.codigo_clave}</code>
                                <button onclick="copyCode('${group.codigo_clave}')" class="text-[#FF9C00] hover:text-orange-600 transition-colors">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                            <p class="text-xs text-blue-700 mb-1 flex items-center gap-2">
                                <i class="fas fa-calendar-check"></i>
                                Fecha de Creación
                            </p>
                            <p class="font-semibold text-gray-800">${new Date(group.fecha_creacion).toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 border-l-4 border-purple-500 p-4 rounded-r mb-6">
                        <p class="text-xs text-purple-700 font-medium mb-1 flex items-center gap-2">
                            <i class="fas fa-user-shield"></i>
                            Creado por
                        </p>
                        <p class="font-semibold text-gray-800">${group.creado_por}</p>
                    </div>
                </div>

                <!-- Proyecto actual -->
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 border-l-4 border-[#FF9C00] p-4 rounded-r mb-6">
                    <p class="text-sm text-orange-700 font-medium mb-1 flex items-center gap-2">
                        <i class="fas fa-project-diagram"></i>
                        Proyecto Actual
                    </p>
                    <p class="text-lg font-bold text-gray-800">${group.project}</p>
                </div>

                <!-- Miembros del equipo -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-friends text-[#FF9C00]"></i>
                        Miembros del Equipo (${group.members_count})
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        ${mockMembers.map(member => `
                            <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    ${member.avatar}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">${member.name}</p>
                                    <p class="text-xs text-gray-600 flex items-center gap-1">
                                        <i class="fas fa-briefcase text-[#FF9C00]"></i>
                                        ${member.role}
                                    </p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <button onclick="closeGroupModal()" 
                    class="w-full bg-[#FF9C00] hover:bg-orange-600 text-white font-medium py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-times-circle"></i>
                    Cerrar
                </button>
            `;

            modal.classList.remove('hidden');
        }

        function closeGroupModal() {
            document.getElementById('groupModal').classList.add('hidden');
        }

        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                toast.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Código copiado: ' + code;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });
        }

        document.getElementById('groupModal').addEventListener('click', function(e) {
            if (e.target === this) closeGroupModal();
        });
    </script>
</x-app-layout>