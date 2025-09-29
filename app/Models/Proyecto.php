<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proyecto extends Model
{
    protected $table = 'proyectos';
    protected $primaryKey = 'id_proyecto';
    public $timestamps = false;

    protected $fillable = [
        'nombre_proyecto',
        'descripción',
        'prioridad',
        'fecha_inicio',
        'fecha_fin',
        'Estado',
        'id_cliente',
        'id_grupo'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime'
    ];

    /**
     * Estados válidos del proyecto
     */
    const ESTADOS = [
        'activo' => 'Activo',
        'completado' => 'Completado',
        'pausado' => 'Pausado',
        'cancelado' => 'Cancelado'
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
     * Relación con Cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    /**
     * Relación con Grupo
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    /**
     * Relación con Area
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }

    /**
     * Relación con Participantes (muchos a muchos)
     */
/**
 * Relación con Participantes (muchos a muchos)
 */
    public function participantes()
    {
        return $this->belongsToMany(
            Participante::class,
            'participante_proyecto',
            'id_proyecto',           // Foreign key en tabla pivot que referencia proyectos
            'id_participante',       // Foreign key en tabla pivot que referencia participantes  
            'id_proyecto',           // Local key en tabla proyectos
            'id_participante'        // Local key en tabla participantes
        )->withPivot('rol_en_proyecto', 'fecha_asignacion');
    }
    /**
     * Relación con Tareas
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'id_proyecto', 'id_proyecto');
    }

    /**
     * Tareas completadas
     */
    public function tareasCompletadas()
    {
        return $this->hasMany(Tarea::class, 'id_proyecto', 'id_proyecto')
                   ->where('estado', 'completado');
    }

    /**
     * Tareas pendientes
     */
    public function tareasPendientes()
    {
        return $this->hasMany(Tarea::class, 'id_proyecto', 'id_proyecto')
                   ->whereIn('estado', ['ausente', 'pendiente']);
    }

    /**
     * Progreso del proyecto
     */
    public function getProgresoAttribute()
    {
        $totalTareas = $this->tareas()->count();
        
        if ($totalTareas === 0) {
            return 0;
        }
        
        $tareasCompletadas = $this->tareasCompletadas()->count();
        
        return round(($tareasCompletadas / $totalTareas) * 100);
    }

    /**
     * Días restantes para completar
     */
    public function getDiasRestantesAttribute()
    {
        if (!$this->fecha_fin) {
            return null;
        }
        
        $diasRestantes = now()->diffInDays($this->fecha_fin, false);
        
        return $diasRestantes > 0 ? $diasRestantes : 0;
    }

    /**
     * Está atrasado
     */
    public function getEstaAtrasadoAttribute()
    {
        if (!$this->fecha_fin || $this->Estado === 'completado') {
            return false;
        }
        
        return now()->gt($this->fecha_fin);
    }

    /**
     * Duración total del proyecto
     */
    public function getDuracionAttribute()
    {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            return null;
        }
        
        return $this->fecha_inicio->diffInDays($this->fecha_fin);
    }

    /**
     * Color de estado para UI
     */
    public function getColorEstadoAttribute()
    {
        return [
            'activo' => 'green',
            'completado' => 'blue',
            'pausado' => 'yellow',
            'cancelado' => 'red'
        ][$this->Estado] ?? 'gray';
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
     * Participante líder del proyecto
     */
    public function getLiderAttribute()
    {
        return $this->participantes()
                   ->wherePivot('rol_en_proyecto', 'like', '%Lead%')
                   ->first();
    }

    /**
     * Scope proyectos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('Estado', 'activo');
    }

    /**
     * Scope por prioridad
     */
    public function scopePorPrioridad($query, $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    /**
     * Scope proyectos próximos a vencer
     */
    public function scopeProximosAVencer($query, $dias = 7)
    {
        return $query->where('fecha_fin', '<=', now()->addDays($dias))
                     ->where('fecha_fin', '>=', now())
                     ->where('Estado', 'activo');
    }

    /**
     * Scope proyectos atrasados
     */
    public function scopeAtrasados($query)
    {
        return $query->where('fecha_fin', '<', now())
                     ->where('Estado', 'activo');
    }

    /**
     * Asignar participante al proyecto
     */
    public function asignarParticipante($participanteId, $rol = null)
    {
        return $this->participantes()->attach($participanteId, [
            'rol_en_proyecto' => $rol,
            'fecha_asignacion' => now()
        ]);
    }

    /**
     * Remover participante del proyecto
     */
    public function removerParticipante($participanteId)
    {
        return $this->participantes()->detach($participanteId);
    }
}