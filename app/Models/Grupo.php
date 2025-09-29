<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;  


class Grupo extends Model
{
    protected $table = 'grupos';
    protected $primaryKey = 'id_grupo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_grupo',
        'descripcion', 
        'codigo_clave',
        'fecha_creacion',
        'creado_por'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime'
    ];

    /**
     * Relación con Proyecto
     * Un grupo tiene un proyecto
     */
    public function proyecto()
    {
        return $this->hasOne(Proyecto::class, 'id_grupo', 'id_grupo');
    }

    /**
     * Relación con Participantes (a través del proyecto)
     */
    public function participantes()
    {
        return $this->hasManyThrough(
            Participante::class,
            Proyecto::class,
            'id_grupo',    // Foreign key en proyectos
            'id_participante', // Foreign key en participantes
            'id_grupo',    // Local key en grupos
            'id_proyecto'  // Local key en proyectos
        )->join('participante_proyecto', function($join) {
            $join->on('participantes.id_participante', '=', 'participante_proyecto.id_participante')
                 ->whereColumn('participante_proyecto.id_proyecto', '=', 'proyectos.id_proyecto');
        })->distinct();
    }

    /**
     * Relación directa con participantes del proyecto
     */
    public function miembros()
    {
        return $this->belongsToMany(
            Participante::class,
            'participante_proyecto',
            'id_proyecto',
            'id_participante',
            'proyecto.id_proyecto',
            'id_participante'
        )->join('proyectos', 'proyectos.id_proyecto', '=', 'participante_proyecto.id_proyecto')
         ->where('proyectos.id_grupo', $this->id_grupo);
    }

    /**
     * Relación con el creador del grupo
     */
    public function creador()
    {
        return $this->belongsTo(Participante::class, 'creado_por', 'id_participante');
    }

    /**
     * Relación con tareas del grupo (a través del proyecto)
     */
    public function tareas()
    {
        return $this->hasManyThrough(
            Tarea::class,
            Proyecto::class,
            'id_grupo',
            'id_proyecto',
            'id_grupo', 
            'id_proyecto'
        );
    }

    /**
     * Obtener participantes directamente
     */
/**
 * Obtener participantes directamente
 */
    public function getParticipantesAttribute()
    {
        if (!$this->proyecto) {
            return collect();
        }
        
        // Usar query builder para evitar problemas de relación
        $participantesIds = DB::table('participante_proyecto')
            ->where('id_proyecto', $this->proyecto->id_proyecto)
            ->pluck('id_participante');
        
        return Participante::whereIn('id_participante', $participantesIds)->get();
    }

    /**
     * Progreso general del grupo
     */
    public function getProgresoAttribute()
    {
        $totalTareas = $this->tareas()->count();
        
        if ($totalTareas === 0) {
            return 0;
        }
        
        $tareasCompletadas = $this->tareas()->where('estado', 'completado')->count();
        
        return round(($tareasCompletadas / $totalTareas) * 100);
    }

    /**
     * Días desde la creación
     */
    public function getDiasActivoAttribute()
    {
        return $this->fecha_creacion ? $this->fecha_creacion->diffInDays(now()) : 0;
    }

    /**
     * Scope para grupos activos
     */
    public function scopeActivos($query)
    {
        return $query->whereHas('proyecto', function($q) {
            $q->where('Estado', 'activo');
        });
    }

    /**
     * Scope para grupos con proyecto
     */
    public function scopeConProyecto($query)
    {
        return $query->whereHas('proyecto');
    }

    /**
     * Generar código único para el grupo
     */
    public static function generarCodigoClave($nombre)
    {
        $prefijo = strtoupper(substr($nombre, 0, 3));
        $numero = rand(1000, 9999);
        $year = now()->year;
        
        return $prefijo . '-' . $numero . '-' . $year;
    }
}