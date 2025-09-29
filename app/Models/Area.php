<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    protected $primaryKey = 'id_area';
    public $timestamps = false;

    protected $fillable = [
        'nombre_area'
    ];

    /**
     * Relación con Participantes
     * Un área tiene muchos participantes
     */
    public function participantes()
    {
        return $this->hasMany(Participante::class, 'id_área', 'id_area');
    }

    /**
     * Contar participantes activos en el área
     */
    public function getParticipantesActivosAttribute()
    {
        return $this->participantes()->whereHas('user')->count();
    }

    /**
     * Scope para áreas con participantes
     */
    public function scopeConParticipantes($query)
    {
        return $query->whereHas('participantes');
    }
}