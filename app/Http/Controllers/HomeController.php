<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Proyecto;
use App\Models\Notificacion;
use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $participante = $user->participante;

        // Obtener tareas pendientes
        $tareasPendientes = Tarea::whereHas('participantes', function($query) use ($participante) {
            $query->where('id_participante', $participante->id_participante);
        })->where('estado', 'pendiente')->count();

        // Obtener proyectos activos
        $proyectosActivos = Proyecto::whereHas('participantes', function($query) use ($participante) {
            $query->where('id_participante', $participante->id_participante);
        })->where('estado', 'activo')->count();

        // Calcular asistencia del mes
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        $diasLaborables = Carbon::now()->diffInWeekdays($inicioMes);
        $diasAsistidos = $diasLaborables - Tarea::whereHas('participantes', function($query) use ($participante) {
            $query->where('id_participante', $participante->id_participante);
        })->whereBetween('fecha_asignacion', [$inicioMes, $finMes])
          ->where('estado', 'ausente')
          ->count();

        $asistenciaPorcentaje = $diasLaborables > 0 ? round(($diasAsistidos / $diasLaborables) * 100) : 100;

        // Obtener notificaciones sin leer
        $notificacionesNuevas = Notificacion::where('participante_id', $participante->id_participante)
            ->where('leido', false)
            ->count();

        // Obtener actividades recientes
        $actividadesRecientes = Tarea::with(['proyecto'])
            ->whereHas('participantes', function($query) use ($participante) {
                $query->where('id_participante', $participante->id_participante);
            })
            ->orderBy('fecha_asignacion', 'desc')
            ->take(3)
            ->get()
            ->map(function($tarea) {
                return [
                    'tipo' => $tarea->estado === 'completado' ? 'completada' : 'asignada',
                    'descripcion' => $tarea->descripcion,
                    'proyecto' => $tarea->proyecto->nombre_proyecto,
                    'fecha' => $tarea->fecha_asignacion,
                ];
            });

        // Por ahora, eventos estáticos hasta que implementemos el módulo de eventos
        $eventos = [
            'scrum' => [
                'title' => 'Capacitación Metodología Scrum y Webinar',
                'date' => 'Lunes 30 de Septiembre, 2025',
                'time' => '2:00 PM - 3:00 PM',
                'location' => 'Online - Google Meet',
                'description' => 'Capacitación completa sobre metodología ágil Scrum, incluyendo roles, ceremonias, artefactos y mejores prácticas.',
                'participants' => ['Todos los equipos', 'Project Managers', 'Scrum Masters', 'Developers'],
                'agenda' => ['Introducción a Scrum', 'Roles: PO, SM, Dev Team', 'Sprint Planning y Daily', 'Sprint Review y Retrospectiva']
            ]
        ];

        return view('page.home_user', compact(
            'tareasPendientes',
            'proyectosActivos',
            'asistenciaPorcentaje',
            'notificacionesNuevas',
            'actividadesRecientes',
            'eventos'
        ));
    }
}