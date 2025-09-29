<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';
    protected $primaryKey = 'id_cargo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_cargo'
    ];

    /**
     * Relación con Participantes
     * Un cargo tiene muchos participantes
     */
    public function participantes()
    {
        return $this->hasMany(Participante::class, 'id_cargo', 'id_cargo');
    }

    /**
     * Contar participantes con este cargo
     */
    public function getParticipantesCountAttribute()
    {
        return $this->participantes()->count();
    }

    /**
     * Scope para cargos técnicos
     */
    public function scopeTecnicos($query)
    {
        return $query->whereIn('nombre_cargo', [
            'Desarrollador Frontend',
            'Desarrollador Backend', 
            'Lead Developer',
            'QA Tester',
            'Data Scientist'
        ]);
    }

    /**
     * Scope para cargos de liderazgo
     */
    public function scopeLiderazgo($query)
    {
        return $query->where('nombre_cargo', 'like', '%Lead%')
                     ->orWhere('nombre_cargo', 'like', '%Manager%')
                     ->orWhere('nombre_cargo', 'like', '%Specialist%');
    }
}