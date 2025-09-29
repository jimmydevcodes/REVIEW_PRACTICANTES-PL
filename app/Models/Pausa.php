<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pausa extends Model
{
    protected $table = 'pausas';
    protected $primaryKey = 'id_pausa';
    public $timestamps = false;

    protected $fillable = [
        'tarea_id',
        'motivo',
        'hora',
        'evidencia',
        'estado'
    ];

    protected $casts = [
        'hora' => 'datetime'
    ];

    /**
     * Estados válidos para la pausa
     */
    const ESTADOS = [
        'pendiente' => 'Pendiente',
        'aprobada' => 'Aprobada',
        'rechazada' => 'Rechazada'
    ];

    /**
     * Relación con Tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_id', 'id_tarea');
    }

    /**
     * Participante que solicitó la pausa (a través de la tarea)
     */
    public function participante()
    {
        return $this->hasOneThrough(
            Participante::class,
            Tarea::class,
            'id_tarea',
            'id_participante',
            'tarea_id',
            'participante_id'
        );
    }

    /**
     * URL de la evidencia (si existe)
     */
    public function getUrlEvidenciaAttribute()
    {
        if (!$this->evidencia) {
            return null;
        }
        
        return Storage::url($this->evidencia);
    }

    /**
     * Nombre del archivo de evidencia
     */
    public function getNombreEvidenciaAttribute()
    {
        if (!$this->evidencia) {
            return null;
        }
        
        return basename($this->evidencia);
    }

    /**
     * Verificar si tiene evidencia
     */
    public function getTieneEvidenciaAttribute()
    {
        return !empty($this->evidencia) && Storage::exists($this->evidencia);
    }

    /**
     * Color de estado para UI
     */
    public function getColorEstadoAttribute()
    {
        return [
            'pendiente' => 'yellow',
            'aprobada' => 'green',
            'rechazada' => 'red'
        ][$this->estado] ?? 'gray';
    }

    /**
     * Icono de estado
     */
    public function getIconoEstadoAttribute()
    {
        return [
            'pendiente' => 'fas fa-clock text-yellow-600',
            'aprobada' => 'fas fa-check-circle text-green-600',
            'rechazada' => 'fas fa-times-circle text-red-600'
        ][$this->estado] ?? 'fas fa-question-circle text-gray-600';
    }

    /**
     * Puede ser aprobada/rechazada
     */
    public function getPuedeSerValidadaAttribute()
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Está aprobada
     */
    public function getEstaAprobadaAttribute()
    {
        return $this->estado === 'aprobada';
    }

    /**
     * Está rechazada
     */
    public function getEstaRechazadaAttribute()
    {
        return $this->estado === 'rechazada';
    }

    /**
     * Tiempo transcurrido desde la solicitud
     */
    public function getTiempoTranscurridoAttribute()
    {
        return $this->hora->diffForHumans();
    }

    /**
     * Días desde la solicitud
     */
    public function getDiasDesdeSolicitudAttribute()
    {
        return $this->hora->diffInDays(now());
    }

    /**
     * Es reciente (menos de 24 horas)
     */
    public function getEsRecienteAttribute()
    {
        return $this->hora->diffInHours(now()) < 24;
    }

    /**
     * Scope por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }

    /**
     * Scope rechazadas
     */
    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazada');
    }

    /**
     * Scope con evidencia
     */
    public function scopeConEvidencia($query)
    {
        return $query->whereNotNull('evidencia');
    }

    /**
     * Scope sin evidencia
     */
    public function scopeSinEvidencia($query)
    {
        return $query->whereNull('evidencia');
    }

    /**
     * Scope recientes (últimas 24 horas)
     */
    public function scopeRecientes($query)
    {
        return $query->where('hora', '>=', now()->subDay());
    }

    /**
     * Aprobar pausa
     */
    public function aprobar()
    {
        return $this->update(['estado' => 'aprobada']);
    }

    /**
     * Rechazar pausa
     */
    public function rechazar()
    {
        return $this->update(['estado' => 'rechazada']);
    }

    /**
     * Eliminar archivo de evidencia al eliminar registro
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pausa) {
            if ($pausa->evidencia && Storage::exists($pausa->evidencia)) {
                Storage::delete($pausa->evidencia);
            }
        });
    }
}