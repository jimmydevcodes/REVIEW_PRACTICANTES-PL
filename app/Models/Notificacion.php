<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificacion';
    public $timestamps = false;

    protected $fillable = [
        'participante_id',
        'tarea_id',
        'tipo',
        'titulo',
        'mensaje',
        'datos_adicionales',
        'leida',
        'fecha_creacion',
        'fecha_lectura'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_lectura' => 'datetime',
        'datos_adicionales' => 'array',
        'leida' => 'boolean'
    ];

    // Tipos de notificación disponibles
    const TIPOS = [
        'tarea_rechazada' => 'Tarea Rechazada',
        'requiere_cambios' => 'Requiere Cambios',
        'tarea_aprobada' => 'Tarea Aprobada',
        'nuevo_comentario' => 'Nuevo Comentario',
        'plazo_vencido' => 'Plazo Vencido',
        'recordatorio' => 'Recordatorio'
    ];

    /**
     * Relación con Participante
     */
    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id', 'id_participante');
    }

    /**
     * Relación con Tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_id', 'id_tarea');
    }

    /**
     * Marcar como leída
     */
    public function marcarLeida()
    {
        $this->update([
            'leida' => true,
            'fecha_lectura' => now()
        ]);
    }

    /**
     * Scope para notificaciones no leídas
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    /**
     * Scope por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}