<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $participante = $user->participante;

        // Obtener áreas con conteo de grupos por área
        $areas = Area::select('areas.*')
            ->selectRaw('(SELECT COUNT(DISTINCT g.id_grupo) 
                FROM participantes p 
                INNER JOIN participante_proyecto pp ON p.id_participante = pp.id_participante
                INNER JOIN proyectos pr ON pp.id_proyecto = pr.id_proyecto
                INNER JOIN grupos g ON pr.id_grupo = g.id_grupo
                WHERE p.id_área = areas.id_area
                AND p.id_participante = ?) as grupos_count', [$participante->id_participante])
            ->get();

        // Obtener grupos del participante a través de sus proyectos
        $groups = Grupo::select('grupos.*')
            ->join('proyectos', 'grupos.id_grupo', '=', 'proyectos.id_grupo')
            ->join('participante_proyecto', 'proyectos.id_proyecto', '=', 'participante_proyecto.id_proyecto')
            ->where('participante_proyecto.id_participante', $participante->id_participante)
            ->with(['proyecto.participantes', 'proyecto.area', 'creador'])
        ->with(['proyecto', 'creador'])
        ->get()
        ->map(function($grupo) {
            return [
                'id_grupo' => $grupo->id_grupo,
                'nombre_grupo' => $grupo->nombre_grupo,
                'descripcion' => $grupo->descripcion,
                'codigo_clave' => $grupo->codigo_clave,
                'fecha_creacion' => $grupo->fecha_creacion,
                'creado_por' => $grupo->creador ? $grupo->creador->nombre . ' ' . $grupo->creador->apellido : 'Sistema',
                'area' => $grupo->proyecto && $grupo->proyecto->area ? $grupo->proyecto->area->nombre_area : 'Sin área',
                'color' => $this->getColorForArea($grupo->proyecto && $grupo->proyecto->area ? $grupo->proyecto->area->nombre_area : ''),
                'project' => $grupo->proyecto ? $grupo->proyecto->nombre_proyecto : 'Sin proyecto asignado',
                'members_count' => $grupo->participantes->count(),
            ];
        });

        return view('groups.index', compact('groups', 'areas'));
    }

    private function getColorForArea($areaName)
    {
        $colors = [
            'Desarrollo Software' => 'blue',
            'Marketing Digital' => 'orange',
            'Soporte' => 'green',
            'default' => 'gray'
        ];

        return $colors[strtolower($areaName)] ?? $colors['default'];
    }
}