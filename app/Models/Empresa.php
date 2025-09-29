<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['nombre', 'ruc'];

    // Relaciones: una empresa tiene múltiples proyectos
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }
}
