<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    protected $fillable = ['nombre', 'fecha_inicio', 'fecha_fin', 'estado', 'proyecto_id'];

    // Un sprint pertenece a un proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}