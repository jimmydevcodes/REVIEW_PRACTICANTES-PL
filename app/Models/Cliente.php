<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $timestamps = false;

    protected $fillable = [
        'nombre_cliente',
        'ruc',
        'dirección',
        'correo_contacto',
        'teléfono'
    ];

    /**
     * Relación con Proyectos
     * Un cliente tiene muchos proyectos
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_cliente', 'id_cliente');
    }

    /**
     * Proyectos activos del cliente
     */
    public function proyectosActivos()
    {
        return $this->hasMany(Proyecto::class, 'id_cliente', 'id_cliente')
                   ->where('Estado', 'activo');
    }

    /**
     * Número total de proyectos
     */
    public function getTotalProyectosAttribute()
    {
        return $this->proyectos()->count();
    }

    /**
     * Número de proyectos activos
     */
    public function getProyectosActivosCountAttribute()
    {
        return $this->proyectosActivos()->count();
    }

    /**
     * Scope para clientes con proyectos activos
     */
    public function scopeConProyectosActivos($query)
    {
        return $query->whereHas('proyectosActivos');
    }

    /**
     * Información de contacto completa
     */
    public function getInfoContactoAttribute()
    {
        $info = [];
        
        if ($this->correo_contacto) {
            $info[] = $this->correo_contacto;
        }
        
        if ($this->teléfono) {
            $info[] = $this->teléfono;
        }
        
        return implode(' | ', $info);
    }
}