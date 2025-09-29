<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Events\TareaActualizada;

class Tarea extends Model
{
    protected $table = 'tareas';
    protected $primaryKey = 'id_tarea';
    public $timestamps = false;

    protected $fillable = [
        'id_proyecto',
        'nombre_tarea',
        'descripción',
        'estado',
        'fecha_asignación',
        'fecha_inicio_asignada',
        'fecha_fin_asignada',
        'fecha_inicio',
        'fecha_fin',
        'ultima_actividad',
        'participante_id',
        'prioridad',
        'estado_asistencia',
        'grupo_fecha_inicio',
        'grupo_fecha_fin'
    ];

    protected $casts = [
        'fecha_asignación' => 'datetime',
        'fecha_inicio_asignada' => 'datetime',
        'fecha_fin_asignada' => 'datetime',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'ultima_actividad' => 'datetime',
        'grupo_fecha_inicio' => 'datetime',
        'grupo_fecha_fin' => 'datetime',
        'participante_id' => 'integer'
    ];

    /**
     * Estados válidos para la tarea
     */
    const ESTADOS = [
        'ausente' => 'Ausente',
        'pendiente' => 'Pendiente', 
        'completado' => 'Completado',
        'incompleto' => 'Incompleto'
    ];

    /**
     * Estados de asistencia válidos
     */
    const ESTADOS_ASISTENCIA = [
        'registro salida anticipada' => 'Registro Salida Anticipada',
        'registro salida tardía' => 'Registro Salida Tardía',
        'incompleto' => 'Incompleto'
    ];

    /**
     * Prioridades válidas
     */
    const PRIORIDADES = [
        'alto' => 'Alto',
        'medio' => 'Medio',
        'bajo' => 'Bajo'
    ];

    /**
     * Relación con Proyecto
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    /**
     * Relación con Participante
     */
    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id', 'id_participante');
    }

    /**
     * Relación con Evidencias
     */
    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'id_tarea', 'id_tarea')
                   ->orderBy('fecha_subida', 'desc');
    }

    /**
     * Relación con Pausas
     */
    public function pausas()
    {
        return $this->hasMany(Pausa::class, 'tarea_id', 'id_tarea')
                   ->orderBy('hora', 'desc');
    }

    /**
     * Última evidencia subida
     */
    public function ultimaEvidencia()
    {
        return $this->hasOne(Evidencia::class, 'id_tarea', 'id_tarea')
                   ->latest('fecha_subida');
    }

    /**
     * Pausas aprobadas
     */
    public function pausasAprobadas()
    {
        return $this->hasMany(Pausa::class, 'tarea_id', 'id_tarea')
                   ->where('estado', 'aprobada');
    }

    /**
     * Evidencias aprobadas
     */
    public function evidenciasAprobadas()
    {
        return $this->hasMany(Evidencia::class, 'id_tarea', 'id_tarea')
                   ->where('estado_validación', 'aprobada');
    }

    /**
     * Verificar si la tarea está atrasada
     */
    public function getEstaAtrasadaAttribute()
    {
        if (!$this->fecha_fin || $this->estado === 'completado') {
            return false;
        }

        // Si la fecha actual es mayor a la fecha fin esperada y no está completada
        return now()->gt($this->fecha_fin);
    }

    /**
     * Días trabajados en la tarea
     */
    public function getDiasTrabajadosAttribute()
    {
        if (!$this->fecha_inicio) {
            return 0;
        }

        $fechaFin = $this->fecha_fin ?: now();
        return $this->fecha_inicio->diffInDays($fechaFin);
    }

    /**
     * Progreso de la tarea basado en evidencias
     */
    public function getProgresoAttribute()
    {
        $totalEvidencias = $this->evidencias()->count();
        
        if ($totalEvidencias === 0) {
            return 0;
        }

        $evidenciasAprobadas = $this->evidenciasAprobadas()->count();
        
        return round(($evidenciasAprobadas / $totalEvidencias) * 100);
    }

    /**
     * Tiempo total dedicado (diferencia entre inicio y fin)
     */
    public function getTiempoTotalAttribute()
    {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            return null;
        }

        return $this->fecha_inicio->diffForHumans($this->fecha_fin, true);
    }

    /**
     * Color de prioridad para UI
     */
    public function getColorPrioridadAttribute()
    {
        return [
            'alto' => 'red',
            'medio' => 'yellow', 
            'bajo' => 'green'
        ][$this->prioridad] ?? 'gray';
    }

    /**
     * Color de estado para UI
     */
    public function getColorEstadoAttribute()
    {
        return [
            'ausente' => 'gray',
            'pendiente' => 'yellow',
            'completado' => 'green',
            'incompleto' => 'red'
        ][$this->estado] ?? 'gray';
    }

    /**
     * Puede subir evidencias
     */
    public function getPuedeSubirEvidenciasAttribute()
    {
        return in_array($this->estado, ['ausente', 'pendiente']);
    }

    /**
     * Puede solicitar pausas
     */
    public function getPuedeSolicitarPausasAttribute()
    {
        return in_array($this->estado, ['ausente', 'pendiente']);
    }

    /**
     * Scope por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope por prioridad
     */
    public function scopePorPrioridad($query, $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    /**
     * Scope por participante
     */
    public function scopePorParticipante($query, $participanteId)
    {
        return $query->where('participante_id', $participanteId);
    }

    /**
     * Scope tareas atrasadas
     */
    public function scopeAtrasadas($query)
    {
        return $query->where('fecha_fin', '<', now())
                     ->whereNotIn('estado', ['completado']);
    }

    /**
     * Scope tareas activas
     */
    public function scopeActivas($query)
    {
        return $query->whereIn('estado', ['pendiente', 'ausente']);
    }

    /**
     * Actualizar fecha de inicio automáticamente
     */
    public function iniciarTarea()
    {
        if (!$this->fecha_inicio) {
            $this->update([
                'fecha_inicio' => now(),
                'estado' => 'pendiente'
            ]);
        }
    }

    /**
     * Actualizar estado cuando se sube una evidencia
     */
    public function registrarEvidencia()
    {
        $now = now();
        
        // Si es la primera evidencia, establecer fecha de inicio
        if (!$this->fecha_inicio) {
            $this->fecha_inicio = $now;
        }
        
        // Actualizar fecha fin y última actividad
        $this->fecha_fin = $now;
        $this->ultima_actividad = $now;
        
        // Si estaba ausente, cambiar a pendiente
        if ($this->estado === 'ausente') {
            $this->estado = 'pendiente';
            
            // Notificar al administrador que hay una nueva evidencia para revisar
            Notificacion::create([
                'participante_id' => $this->participante_id,
                'tarea_id' => $this->id_tarea,
                'tipo' => 'nueva_evidencia',
                'titulo' => 'Nueva Evidencia para Revisar',
                'mensaje' => "Se ha subido una nueva evidencia para la tarea '{$this->nombre_tarea}'",
                'fecha_creacion' => now()
            ]);
        }
        
        $this->save();
        
        // Disparar evento personalizado para notificar cambio
        event(new TareaActualizada($this));
    }

    /**
     * Actualizar estado cuando se borran todas las evidencias
     */
    public function actualizarPorBorradoEvidencias()
    {
        $now = now();
        
        // Registrar la última actividad
        $this->ultima_actividad = $now;
        
        // Si no quedan evidencias
        if ($this->evidencias()->count() === 0) {
            // Cambiar a estado ausente
            $this->estado = 'ausente';
            // Limpiar fechas de inicio y fin
            $this->fecha_inicio = null;
            $this->fecha_fin = null;
            // Limpiar estado de asistencia
            $this->estado_asistencia = null;
        }
        
        $this->save();
    }

    /**
     * Actualizar fecha de fin automáticamente y calcular estado de asistencia
     */
    public function actualizarFin()
    {
        $now = now();
        $this->fecha_fin = $now;
        $this->ultima_actividad = $now;
        
        // Calcular estado de asistencia
        $this->calcularEstadoAsistencia();
        
        $this->save();
    }

    /**
     * Calcular estado de asistencia basado en evidencias y fechas límite
     * Solo se calcula cuando la tarea está completada (aprobada por admin)
     */
    public function calcularEstadoAsistencia()
    {
        // Solo calcular estado de asistencia si la tarea está completada
        if ($this->estado !== 'completado') {
            $this->estado_asistencia = null;
            return;
        }

        // Si no hay evidencias aprobadas, no hay estado de asistencia
        if ($this->evidenciasAprobadas()->count() === 0) {
            $this->estado_asistencia = null;
            return;
        }

        // Si no hay fecha de última modificación, no podemos calcular el estado
        if (!$this->fecha_fin) {
            $this->estado_asistencia = null;
            return;
        }

        // Usar la fecha fin del grupo como límite final
        $fechaLimite = $this->grupo_fecha_fin ?? $this->fecha_fin_asignada;

        if (!$fechaLimite) {
            return;
        }

        // Comparar con la fecha límite
        if ($this->fecha_fin->lte($fechaLimite)) {
            $this->estado_asistencia = 'registro salida anticipada';
        } else {
            $this->estado_asistencia = 'registro salida tardía';
        }
    }

    /**
     * Aprobar tarea por el administrador
     */
    public function aprobarTarea($observaciones = null)
    {
        $this->estado = 'completado';
        $this->calcularEstadoAsistencia();
        $this->save();

        // Crear notificación de aprobación
        Notificacion::create([
            'participante_id' => $this->participante_id,
            'tarea_id' => $this->id_tarea,
            'tipo' => 'tarea_aprobada',
            'titulo' => '¡Tarea Aprobada!',
            'mensaje' => $observaciones ?? 'Tu tarea ha sido aprobada correctamente.',
            'fecha_creacion' => now()
        ]);

        event(new TareaActualizada($this));
    }

    /**
     * Rechazar tarea por el administrador
     */
    public function rechazarTarea($observaciones)
    {
        $this->estado = 'pendiente'; // Vuelve a pendiente para correcciones
        $this->estado_asistencia = null;
        $this->save();

        // Crear notificación de rechazo
        Notificacion::create([
            'participante_id' => $this->participante_id,
            'tarea_id' => $this->id_tarea,
            'tipo' => 'tarea_rechazada',
            'titulo' => 'Tarea Requiere Cambios',
            'mensaje' => $observaciones,
            'fecha_creacion' => now()
        ]);

        event(new TareaActualizada($this));
    }

    /**
     * Validar si una tarea está dentro del rango permitido del grupo
     */
    public function dentroDeTiempoGrupo()
    {
        if (!$this->grupo_fecha_inicio || !$this->grupo_fecha_fin) {
            return true; // Si no hay fechas de grupo, asumimos que está en tiempo
        }

        $now = now();
        return $now->between($this->grupo_fecha_inicio, $this->grupo_fecha_fin);
    }
}