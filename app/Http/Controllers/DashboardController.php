<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Futuro: Logica para estadisticas, tareas de usuario, etc.
        // $stats = [
        //     'tareas' => Task::where('user_id', Auth::id())->count(),
        //     'asistencia' => Attendance::percentage(Auth::id()),
        // ];
        // return view('dashboard.index', compact('stats'));
        
        return view('page.home_user');
    }

    /**
     * Futuro: Estadísticas del usuario
     */
    public function stats()
    {
        $stats = [
            'tareas' => 12, // Futuro: Task::count()
            'horarios' => 5,
            'asistencia' => 98,
        ];

        return response()->json($stats);
    }

    /**
     * Futuro: Reportes
     */
    public function reports()
    {
        return view('dashboard.reports');
    }
}