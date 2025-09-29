<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con Participante
     * Un usuario puede tener un participante asociado
     */
    public function participante()
    {
        return $this->hasOne(Participante::class, 'user_id');
    }

    /**
     * Verificar si el usuario es un participante
     */
    public function isParticipant()
    {
        return $this->participante !== null;
    }

    /**
     * Accessor para obtener el avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->participante && $this->participante->foto) {
            return asset('storage/' . $this->participante->foto);
        }
        
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=FF9C00&color=fff";
    }
}