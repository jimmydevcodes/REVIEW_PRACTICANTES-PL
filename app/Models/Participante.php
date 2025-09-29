<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    public $timestamps = false;
    
    protected $primaryKey = 'id_participante';

    protected $fillable = [
        'id_participante',
        'user_id',
        'nombre',
        'apellido',
        'correo',
        'teléfono',
        'foto',
        'id_área',
        'id_cargo'
    ];

    protected $casts = [
        'id_participante' => 'integer',
        'user_id' => 'integer',
        'id_área' => 'integer',
        'id_cargo' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con proyectos
     * Un participante puede estar en varios proyectos
     */
    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'participante_proyecto', 
            'id_participante', 'id_proyecto')
            ->withPivot('rol_en_proyecto', 'fecha_asignacion');
    }

    /**
     * Relación con grupos (a través de proyectos)
     * Un participante puede estar en varios grupos a través de sus proyectos
     */
    public function grupos()
    {
        return $this->hasManyThrough(
            Grupo::class,
            Proyecto::class,
            'id_grupo',  // Foreign key en proyectos
            'id_grupo',  // Foreign key en grupos
            'id_participante', // Local key en participantes
            'id_grupo'   // Local key en proyectos
        );
    }

    /**
     * Relación con el área
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_área', 'id_area');
    }

    /**
     * Relación con el cargo
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_cargo', 'id_cargo');
    }
}
