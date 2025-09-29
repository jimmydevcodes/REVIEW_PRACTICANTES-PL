<x-app-layout>
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('tasks.index') }}" class="text-gray-500 hover:text-orange-600 transition-colors">
                        <i class="fas fa-tasks mr-1"></i>Mis Tareas
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-900 font-medium">{{ $grupo->nombre_grupo }}</span>
                </li>
            </ol>
        </nav>

        <!-- Header del Grupo -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">{{ $grupo->nombre_grupo }}</h1>
                        <p class="text-orange-100 mt-1">{{ $grupo->proyecto->nombre_proyecto ?? 'Sin proyecto asignado' }}</p>
                    </div>
                    <div class="text-right">
                        @if($grupo->proyecto && $grupo->proyecto->cliente)
                            <p class="text-orange-100 text-sm">Cliente</p>
                            <p class="text-white font-medium">{{ $grupo->proyecto->cliente->nombre_cliente }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Descripción -->
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Descripción del Grupo</h3>
                        <p class="text-gray-600">{{ $grupo->descripcion ?? 'Sin descripción disponible' }}</p>
                    </div>
                    
                    <!-- Estadísticas de Tareas -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Mis Estadísticas</h3>
                        <div class="space-y-2">
                            @php
                                $total = $tareas->count();
                                $pendientes = $tareas->where('estado', 'pendiente')->count();
                                $completadas = $tareas->where('estado', 'completado')->count();
                                $ausentes = $tareas->where('estado', 'ausente')->count();
                                $incompletas = $tareas->where('estado', 'incompleto')->count();
                            @endphp
                            
                            <div class="flex justify-between text-sm">
                                <span>Total:</span>
                                <span class="font-medium">{{ $total }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-yellow-600">Pendientes:</span>
                                <span class="font-medium text-yellow-600">{{ $pendientes }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-green-600">Completadas:</span>
                                <span class="font-medium text-green-600">{{ $completadas }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Ausentes:</span>
                                <span class="font-medium text-gray-500">{{ $ausentes }}</span>
                            </div>
                            @if($incompletas > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-red-600">Incompletas:</span>
                                    <span class="font-medium text-red-600">{{ $incompletas }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Tareas -->
        @if($tareas->count() > 0)
            <div class="space-y-6">
                @foreach($tareas as $tarea)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Header de la Tarea -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $tarea->nombre_tarea }}</h3>
                                        
                                        <!-- Prioridad Badge -->
                                        @php
                                            $prioridadColors = [
                                                'alto' => 'bg-red-100 text-red-800',
                                                'medio' => 'bg-yellow-100 text-yellow-800',
                                                'bajo' => 'bg-green-100 text-green-800'
                                            ];
                                            $prioridadClass = $prioridadColors[strtolower($tarea->prioridad)] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $prioridadClass }}">
                                            {{ ucfirst($tarea->prioridad) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 mb-4">{{ $tarea->descripción }}</p>
                                    
                                    <!-- Fechas -->
                                    <div class="space-y-4">
                                        <!-- Horario Asignado -->
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Horario Asignado</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <span class="text-xs text-gray-500">Debe comenzar:</span>
                                                    <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($tarea->fecha_inicio_asignada)->timezone('America/Lima')->format('d/m/Y h:i A') }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-xs text-gray-500">Debe terminar:</span>
                                                    <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($tarea->fecha_fin_asignada)->timezone('America/Lima')->format('d/m/Y h:i A') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Horario Real -->
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Registro de Actividad</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <span class="text-xs text-gray-500">Fecha Asignación:</span>
                                                    <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($tarea->fecha_asignación)->timezone('America/Lima')->format('d/m/Y h:i A') }}</p>
                                                </div>
                                                
                                                @if($tarea->fecha_inicio)
                                                    <div>
                                                        <span class="text-xs text-gray-500">Comenzó:</span>
                                                        <p class="text-sm font-medium text-blue-600">{{ \Carbon\Carbon::parse($tarea->fecha_inicio)->timezone('America/Lima')->format('d/m/Y h:i A') }}</p>
                                                    </div>
                                                @endif
                                                
                                                @if($tarea->fecha_fin)
                                                    <div>
                                                        <span class="text-xs text-gray-500">Última actividad:</span>
                                                        <p class="text-sm font-medium text-purple-600">{{ \Carbon\Carbon::parse($tarea->fecha_fin)->timezone('America/Lima')->format('d/m/Y h:i A') }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($tarea->estado_asistencia)
                                                <div class="mt-2">
                                                    @php
                                                        $asistenciaColors = [
                                                            'registro salida anticipada' => 'bg-green-100 text-green-800',
                                                            'registro salida tardía' => 'bg-orange-100 text-orange-800',
                                                            'incompleto' => 'bg-red-100 text-red-800'
                                                        ];
                                                        $asistenciaClass = $asistenciaColors[$tarea->estado_asistencia] ?? 'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $asistenciaClass }}">
                                                        {{ ucfirst($tarea->estado_asistencia) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Estados -->
                                <div class="flex flex-col items-end space-y-2">
                                    <!-- Estado de Tarea -->
                                    @php
                                        $estadoColors = [
                                            'ausente' => 'bg-gray-100 text-gray-800',
                                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                                            'completado' => 'bg-green-100 text-green-800',
                                            'incompleto' => 'bg-red-100 text-red-800'
                                        ];
                                        $estadoClass = $estadoColors[strtolower($tarea->estado)] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    
                                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $estadoClass }}">
                                        {{ ucfirst($tarea->estado) }}
                                    </span>
                                    
                                    <!-- Estado de Asistencia -->
                                    @if($tarea->estado_asistencia && $tarea->estado_asistencia !== 'ausente')
                                        @php
                                            $asistenciaColors = [
                                                'registro salida anticipada' => 'bg-blue-100 text-blue-800',
                                                'registro salida tardía' => 'bg-orange-100 text-orange-800',
                                                'incompleto' => 'bg-red-100 text-red-800'
                                            ];
                                            $asistenciaClass = $asistenciaColors[strtolower($tarea->estado_asistencia)] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        
                                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $asistenciaClass }}">
                                            {{ ucfirst($tarea->estado_asistencia) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contenido de la Tarea -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                
                                <!-- Evidencias -->
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-medium text-gray-900">Evidencias</h4>
                                        @if($tarea->estado !== 'completado' && $tarea->estado !== 'incompleto')
                                            <button onclick="openUploadModal({{ $tarea->id_tarea }})" 
                                                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                                <i class="fas fa-upload mr-2"></i>Subir Archivo
                                            </button>
                                        @endif
                                    </div>
                                    
                                    @if($tarea->evidencias->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($tarea->evidencias as $evidencia)
                                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                                            @if($evidencia->tipo_archivo === 'pdf')
                                                                <i class="fas fa-file-pdf text-red-600"></i>
                                                            @else
                                                                <i class="fas fa-file-word text-blue-600"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">
                                                                {{ basename($evidencia->archivo) }}
                                                            </p>
                                                            <p class="text-xs text-gray-500">
                                                                {{ \Carbon\Carbon::parse($evidencia->fecha_subida)->timezone('America/Lima')->format('d/m/Y h:i A') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center space-x-2">
                                                        <!-- Estado de Validación -->
                                                        @php
                                                            $validacionColors = [
                                                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                                                'aprobada' => 'bg-green-100 text-green-800',
                                                                'rechazada' => 'bg-red-100 text-red-800'
                                                            ];
                                                            $validacionClass = $validacionColors[$evidencia->estado_validacion] ?? 'bg-gray-100 text-gray-800';
                                                        @endphp
                                                        
                                                        <span class="px-2 py-1 text-xs font-medium rounded {{ $validacionClass }}">
                                                            {{ ucfirst($evidencia->estado_validacion) }}
                                                        </span>
                                                        
                                                        <!-- Acciones -->
                                                        <div class="flex items-center space-x-2">
                                                            <a href="{{ Storage::url($evidencia->archivo) }}" target="_blank"
                                                               class="text-orange-600 hover:text-orange-700 transition-colors">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                            
                                                            <button onclick="openRenameModal({{ $tarea->id_tarea }}, {{ $evidencia->id_evidencia }}, '{{ basename($evidencia->archivo) }}')"
                                                                    class="text-blue-600 hover:text-blue-700 transition-colors">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            
                                                            <button onclick="deleteEvidence({{ $tarea->id_tarea }}, {{ $evidencia->id_evidencia }})"
                                                                    class="text-red-600 hover:text-red-700 transition-colors">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-upload text-gray-400 text-2xl"></i>
                                            </div>
                                            <p class="text-gray-500">No hay evidencias subidas</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Pausas -->
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-medium text-gray-900">Pausas</h4>
                                        @if($tarea->estado !== 'completado' && $tarea->estado !== 'incompleto')
                                            <button onclick="openPauseModal({{ $tarea->id_tarea }})" 
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                                <i class="fas fa-pause mr-2"></i>Solicitar Pausa
                                            </button>
                                        @endif
                                    </div>
                                    
                                    @if($tarea->pausas->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($tarea->pausas as $pausa)
                                                <div class="p-4 bg-gray-50 rounded-lg">
                                                    <div class="flex items-start justify-between mb-2">
                                                        <p class="text-sm font-medium text-gray-900">{{ $pausa->motivo }}</p>
                                                        
                                                        @php
                                                            $pausaColors = [
                                                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                                                'aprobada' => 'bg-green-100 text-green-800',
                                                                'rechazada' => 'bg-red-100 text-red-800'
                                                            ];
                                                            $pausaClass = $pausaColors[$pausa->estado] ?? 'bg-gray-100 text-gray-800';
                                                        @endphp
                                                        
                                                        <span class="px-2 py-1 text-xs font-medium rounded {{ $pausaClass }}">
                                                            {{ ucfirst($pausa->estado) }}
                                                        </span>
                                                    </div>
                                                    
                                                    <p class="text-xs text-gray-500 mb-2">
                                                        {{ \Carbon\Carbon::parse($pausa->hora)->format('d/m/Y H:i') }}
                                                    </p>
                                                    
                                                    @if($pausa->evidencia)
                                                        <a href="{{ Storage::url($pausa->evidencia) }}" target="_blank"
                                                           class="inline-flex items-center text-orange-600 hover:text-orange-700 text-sm transition-colors">
                                                            <i class="fas fa-paperclip mr-1"></i>Ver evidencia
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-pause text-gray-400 text-2xl"></i>
                                            </div>
                                            <p class="text-gray-500">No hay pausas solicitadas</p>
                                        </div>
                                    @endif
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Sin tareas asignadas</h3>
                    <p class="text-gray-600">No tienes tareas asignadas en este grupo aún.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- CSRF Token Meta -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal para Subir Evidencia -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Subir Evidencia</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="uploadTaskId" name="task_id">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Archivo (PDF o WORD - Máx. 10MB)
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="archivo" name="archivo" accept=".pdf,.docx,.doc" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOCX, DOC. Tamaño máximo: 10MB</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones (opcional)</label>
                    <textarea id="observaciones" name="observaciones" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                              placeholder="Agrega cualquier observación..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="closeUploadModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                        Subir Archivo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Solicitar Pausa -->
<div id="pauseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Solicitar Pausa</h3>
                <button onclick="closePauseModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="pauseForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="pauseTaskId" name="task_id">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo de la pausa</label>
                    <textarea id="motivo" name="motivo" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Explica el motivo de la pausa..."></textarea>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Evidencia (opcional)</label>
                    <input type="file" id="evidenciaPausa" name="evidencia" accept=".pdf,.docx,.doc,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">PDF, WORD o imagen</p>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="closePauseModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Solicitar Pausa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variables globales
let currentTaskId = null;

// Funciones para modal de subir evidencia
function openUploadModal(taskId) {
    currentTaskId = taskId;
    document.getElementById('uploadTaskId').value = taskId;
    document.getElementById('uploadModal').classList.remove('hidden');
    document.getElementById('uploadModal').classList.add('flex');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadModal').classList.remove('flex');
    document.getElementById('uploadForm').reset();
}

// Funciones para modal de solicitar pausa
function openPauseModal(taskId) {
    currentTaskId = taskId;
    document.getElementById('pauseTaskId').value = taskId;
    document.getElementById('pauseModal').classList.remove('hidden');
    document.getElementById('pauseModal').classList.add('flex');
}

function closePauseModal() {
    document.getElementById('pauseModal').classList.add('hidden');
    document.getElementById('pauseModal').classList.remove('flex');
    document.getElementById('pauseForm').reset();
}

// Manejar envío del formulario de evidencia
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const fileInput = this.querySelector('input[name="archivo"]');
    if (fileInput.files[0] && fileInput.files[0].size > 10 * 1024 * 1024) {
        alert('El archivo es demasiado grande. El tamaño máximo permitido es 10MB.');
        return;
    }
    
    const formData = new FormData(this);
    const taskId = document.getElementById('uploadTaskId').value;
    
    try {
        const csrfToken = this.querySelector('input[name="_token"]').value;
        
        const response = await fetch(`/tasks/${taskId}/upload-evidence`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.error || 'Error al subir la evidencia');
        }
        
        if (result.success) {
            alert('Evidencia subida correctamente');
            closeUploadModal();
            location.reload();
        } else {
            alert('Error al subir evidencia: ' + (result.message || 'Error desconocido'));
        }
    } catch (error) {
        console.error('Error al subir evidencia:', error);
        alert(error.message || 'Error al subir evidencia. Por favor, intente nuevamente.');
    }
});

// Manejar envío del formulario de pausa
document.getElementById('pauseForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const taskId = document.getElementById('pauseTaskId').value;
    
    try {
        // Get CSRF token from the form's _token field since we're using @csrf
        const csrfToken = this.querySelector('input[name="_token"]').value;
        
        const response = await fetch(`/tasks/${taskId}/request-pause`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Solicitud de pausa enviada correctamente');
            closePauseModal();
            location.reload(); // Recargar para mostrar la nueva pausa
        } else {
            alert('Error al solicitar pausa: ' + result.message);
        }
    } catch (error) {
        console.error('Error al solicitar pausa:', error);
        alert('Error al solicitar pausa. Por favor, intente nuevamente.');
    }
});

// Cerrar modales al hacer clic fuera
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUploadModal();
    }
});

document.getElementById('pauseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePauseModal();
    }
});

// Funciones para manejar evidencias
async function deleteEvidence(taskId, evidenciaId) {
    try {
        if (!confirm('¿Estás seguro de que deseas eliminar esta evidencia?')) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const response = await fetch(`/tasks/${taskId}/evidence/${evidenciaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            alert('Evidencia eliminada correctamente');
            window.location.reload();
        } else {
            throw new Error(result.error || 'Error al eliminar la evidencia');
        }
    } catch (error) {
        console.error('Error al eliminar evidencia:', error);
        alert('Error al eliminar la evidencia: ' + error.message);
    }
}

async function openRenameModal(taskId, evidenciaId, currentName) {
    try {
        const nuevoNombre = prompt('Ingresa el nuevo nombre para la evidencia:', currentName);
        
        if (!nuevoNombre) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const response = await fetch(`/tasks/${taskId}/evidence/${evidenciaId}/rename`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nuevo_nombre: nuevoNombre
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            alert('Evidencia renombrada correctamente');
            window.location.reload();
        } else {
            throw new Error(result.error || 'Error al renombrar la evidencia');
        }
    } catch (error) {
        console.error('Error al renombrar evidencia:', error);
        alert('Error al renombrar la evidencia: ' + error.message);
    }
}
</script>
</x-app-layout>