<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    protected $table = 'participantes';
    protected $primaryKey = 'id_participante';
    public $timestamps = false;
    
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

    /**
     * Relación con User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con Proyectos (muchos a muchos)
     */
    public function proyectos()
    {
        return $this->belongsToMany(
            Proyecto::class,
            'participante_proyecto',
            'id_participante',       // FK en tabla pivote que referencia participantes
            'id_proyecto',           // FK en tabla pivote que referencia proyectos
            'id_participante',       // PK en tabla participantes
            'id_proyecto'            // PK en tabla proyectos
        )
        ->withPivot('rol_en_proyecto', 'fecha_asignacion');
    }

    /**
     * Proyectos activos del participante
     */
    public function proyectosActivos()
    {
        return $this->proyectos()->where('proyectos.Estado', 'activo');
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

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscarPorNombre($query, $nombre)
    {
        return $query->where('nombre', 'like', "%{$nombre}%")
                     ->orWhere('apellido', 'like', "%{$nombre}%");
    }
}