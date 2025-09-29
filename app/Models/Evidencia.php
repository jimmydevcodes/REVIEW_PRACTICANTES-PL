<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Evidencia extends Model
{
    protected $table = 'evidencias';
    protected $primaryKey = 'id_evidencia';
    public $timestamps = false;

    protected $fillable = [
        'id_tarea',
        'archivo',
        'tipo_archivo',
        'fecha_subida',
        'estado_validación',
        'observaciones_validacion'
    ];

    protected $casts = [
        'fecha_subida' => 'datetime'
    ];

    /**
     * Estados de validación válidos
     */
    const ESTADOS_VALIDACION = [
        'pendiente' => 'Pendiente',
        'aprobada' => 'Aprobada',
        'rechazada' => 'Rechazada'
    ];

    /**
     * Tipos de archivo válidos
     */
    const TIPOS_ARCHIVO = [
        'pdf' => 'PDF',
        'docx' => 'Word (DOCX)',
        'doc' => 'Word (DOC)',
        'imagen' => 'Imagen'
    ];

    /**
     * Relación con Tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'id_tarea', 'id_tarea');
    }

    /**
     * URL completa del archivo
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->archivo);
    }

    /**
     * URL de descarga
     */
    public function getUrlDescargaAttribute()
    {
        return route('evidencias.download', $this->id_evidencia);
    }

    /**
     * Nombre del archivo sin ruta
     */
    public function getNombreArchivoAttribute()
    {
        return basename($this->archivo);
    }

    /**
     * Tamaño del archivo formateado
     */
    public function getTamañoFormateadoAttribute()
    {
        if (!Storage::exists($this->archivo)) {
            return 'Archivo no encontrado';
        }

        $bytes = Storage::size($this->archivo);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Icono según tipo de archivo
     */
    public function getIconoAttribute()
    {
        return [
            'pdf' => 'fas fa-file-pdf text-red-600',
            'docx' => 'fas fa-file-word text-blue-600',
            'doc' => 'fas fa-file-word text-blue-600',
            'imagen' => 'fas fa-file-image text-green-600'
        ][$this->tipo_archivo] ?? 'fas fa-file text-gray-600';
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
        ][$this->estado_validación] ?? 'gray';
    }

    /**
     * Verificar si el archivo existe
     */
    public function getArchivoExisteAttribute()
    {
        return Storage::exists($this->archivo);
    }

    /**
     * Puede ser validada (no está rechazada ni aprobada)
     */
    public function getPuedeSerValidadaAttribute()
    {
        return $this->estado_validación === 'pendiente';
    }

    /**
     * Está aprobada
     */
    public function getEstaAprobadaAttribute()
    {
        return $this->estado_validación === 'aprobada';
    }

    /**
     * Está rechazada
     */
    public function getEstaRechazadaAttribute()
    {
        return $this->estado_validación === 'rechazada';
    }

    /**
     * Scope por estado de validación
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado_validación', $estado);
    }

    /**
     * Scope pendientes de validación
     */
    public function scopePendientes($query)
    {
        return $query->where('estado_validación', 'pendiente');
    }

    /**
     * Scope aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado_validación', 'aprobada');
    }

    /**
     * Scope rechazadas
     */
    public function scopeRechazadas($query)
    {
        return $query->where('estado_validación', 'rechazada');
    }

    /**
     * Scope por tipo de archivo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_archivo', $tipo);
    }

    /**
     * Aprobar evidencia
     */
    public function aprobar($observaciones = null)
    {
        return $this->update([
            'estado_validación' => 'aprobada',
            'observaciones_validacion' => $observaciones
        ]);
    }

    /**
     * Rechazar evidencia
     */
    public function rechazar($observaciones = null)
    {
        return $this->update([
            'estado_validación' => 'rechazada',
            'observaciones_validacion' => $observaciones
        ]);
    }

    /**
     * Eliminar archivo físico al eliminar registro y actualizar estado de tarea
     */
    protected static function boot()
    {
        parent::boot();

        // Cuando se crea una nueva evidencia
        static::created(function ($evidencia) {
            $tarea = $evidencia->tarea;
            if ($tarea) {
                // Actualizar la tarea cuando se sube una evidencia
                $tarea->registrarEvidencia();
            }
        });

        // Cuando se elimina una evidencia
        static::deleting(function ($evidencia) {
            if (Storage::exists($evidencia->archivo)) {
                Storage::delete($evidencia->archivo);
            }

            // Después de eliminar, verificar si quedan evidencias
            $tarea = $evidencia->tarea;
            if ($tarea) {
                // Si era la última evidencia, actualizar estado
                if ($evidencia->tarea->evidencias()->count() <= 1) {
                    $tarea->actualizarPorBorradoEvidencias();
                }
            }
        });
    }
}