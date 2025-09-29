<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    protected $table = 'participantes';
    protected $primaryKey = 'id_participante';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_participante', 'user_id', 'nombre', 'apellido', 'correo', 
        'teléfono', 'foto', 'id_área', 'id_cargo'
    ];

    /**
     * Relación con User
     * Un participante pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con Area
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_área', 'id_area');
    }

    /**
     * Relación con Cargo
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_cargo', 'id_cargo');
    }

    /**
     * Relación con Proyectos
     */
    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'participante_proyecto', 'id_participante', 'id_proyecto')
                    ->withPivot('rol_en_proyecto', 'fecha_asignacion');
    }

    /**
     * Relación con Tareas
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'participante_id', 'id_participante');
    }

    /**
     * Relación con Grupos (a través de proyectos)
     */
    public function grupos()
    {
        return $this->hasManyThrough(
            Grupo::class,
            Proyecto::class,
            'id_proyecto', // Foreign key on participante_proyecto table
            'id_grupo',    // Foreign key on proyectos table
            'id_participante', // Local key on participantes table
            'id_grupo'     // Local key on grupos table
        )->join('participante_proyecto', function($join) {
            $join->on('participante_proyecto.id_proyecto', '=', 'proyectos.id_proyecto')
                 ->where('participante_proyecto.id_participante', '=', $this->id_participante);
        });
    }

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    /**
     * Accessor para avatar
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        
        return "https://ui-avatars.com/api/?name=" . urlencode($this->nombre_completo) . "&background=FF9C00&color=fff";
    }

    /**
     * Buscar participante por user_id
     */
    public static function findByUserId($userId)
    {
        return static::where('user_id', $userId)->first();
    }

    /**
     * Buscar participante por email del usuario
     */
    public static function findByUserEmail($email)
    {
        return static::whereHas('user', function($query) use ($email) {
            $query->where('email', $email);
        })->first();
    }
}