<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Tarea;
use App\Models\Evidencia;
use App\Models\Pausa;
use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Vista principal - Grupos asignados al usuario
     */
    public function index()
    {
        // Obtener el participante del usuario autenticado
        $participante = Auth::user()->participante;
        
        if (!$participante) {
            return redirect()->route('dashboard')->with('error', 
                'Tu cuenta no está registrada como participante. Contacta al administrador.');
        }

        // Obtener grupos usando query builder para evitar problemas de relación
        $grupos = DB::table('grupos')
            ->join('proyectos', 'grupos.id_grupo', '=', 'proyectos.id_grupo')
            ->join('participante_proyecto', 'proyectos.id_proyecto', '=', 'participante_proyecto.id_proyecto')
            ->where('participante_proyecto.id_participante', $participante->id_participante)
            ->select('grupos.*')
            ->distinct()
            ->get();

        // Convertir a colección de modelos
        $grupos = $grupos->map(function($grupo) {
            return Grupo::find($grupo->id_grupo);
        });

        return view('tasks.index', compact('grupos', 'participante'));
    }

    /**
     * Tareas específicas de un grupo
     */
    public function showGroupTasks($groupId)
    {
        $participante = Auth::user()->participante;
        
        if (!$participante) {
            return redirect()->route('tasks.index')->with('error', 
                'Participante no encontrado.');
        }
        
        // Obtener grupo de forma simple
        $grupo = Grupo::with(['proyecto.cliente'])->findOrFail($groupId);

        // Verificar que el participante pertenece al grupo
        $perteneceAlGrupo = DB::table('participante_proyecto')
            ->join('proyectos', 'participante_proyecto.id_proyecto', '=', 'proyectos.id_proyecto')
            ->where('proyectos.id_grupo', $groupId)
            ->where('participante_proyecto.id_participante', $participante->id_participante)
            ->exists();

        if (!$perteneceAlGrupo) {
            return redirect()->route('tasks.index')->with('error', 
                'No tienes acceso a este grupo.');
        }

        // Obtener tareas del participante en este grupo
        $tareas = Tarea::where('participante_id', $participante->id_participante)
                      ->whereHas('proyecto', function($query) use ($groupId) {
                          $query->where('id_grupo', $groupId);
                      })
                      ->with(['evidencias', 'pausas', 'proyecto'])
                      ->orderBy('prioridad', 'desc')
                      ->orderBy('fecha_asignacion', 'desc')
                      ->get();

        return view('tasks.group-tasks', compact('grupo', 'tareas', 'participante'));
    }

    /**
     * Subir evidencia para una tarea
     */
    public function uploadEvidence(Request $request, $taskId)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:pdf,docx,doc|max:10240', // 10MB
            'observaciones' => 'nullable|string|max:500'
        ]);

        $tarea = Tarea::findOrFail($taskId);
        $participante = Auth::user()->participante;
        
        Log::info('Upload Evidence Debug:', [
            'tarea_id' => $taskId,
            'tarea_participante_id' => $tarea->participante_id,
            'user_id' => Auth::id(),
            'participante' => $participante ? [
                'id' => $participante->id_participante,
                'nombre' => $participante->nombre
            ] : null
        ]);

        // Verificar que la tarea pertenece al usuario actual
        if (!$participante) {
            return response()->json(['error' => 'No se encontró el perfil de participante asociado a tu usuario'], 403);
        }
        
        Log::info('Comparación de IDs:', [
            'tarea_participante_id' => $tarea->participante_id,
            'tipo_tarea_participante_id' => gettype($tarea->participante_id),
            'participante_id' => $participante->id_participante,
            'tipo_participante_id' => gettype($participante->id_participante)
        ]);
        
        if ($tarea->participante_id != $participante->id_participante) {
            return response()->json(['error' => 'Esta tarea está asignada a otro participante'], 403);
        }

        // Subir archivo
        $archivo = $request->file('archivo');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $rutaArchivo = $archivo->storeAs('evidencias', $nombreArchivo, 'public');

        // Crear evidencia
        $evidencia = Evidencia::create([
            'id_tarea' => $taskId,
            'archivo' => $rutaArchivo,
            'tipo_archivo' => $archivo->getClientOriginalExtension(),
            'fecha_subida' => now()->timezone('America/Lima'),
            'estado_validación' => 'pendiente',
            'observaciones_validacion' => $request->observaciones
        ]);

        // Si es la primera evidencia, establecer fecha_inicio y cambiar estado
        if ($tarea->evidencias()->count() === 0) {
            $tarea->update([
                'fecha_inicio' => now()->timezone('America/Lima'),
                'estado' => 'pendiente', // Cambiar de ausente a pendiente
            ]);
        }

        // Siempre actualizar la fecha_fin con la última modificación
        $tarea->update([
            'fecha_fin' => now()->timezone('America/Lima')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evidencia subida correctamente',
            'evidencia' => $evidencia
        ]);
    }

    /**
     * Solicitar pausa para una tarea
     */
    public function requestPause(Request $request, $taskId)
    {
        $request->validate([
            'motivo' => 'required|string|max:500',
            'evidencia' => 'nullable|file|mimes:pdf,docx,doc,jpg,jpeg,png|max:5120'
        ]);

        $tarea = Tarea::findOrFail($taskId);
        
        // Verificar permisos
        $participante = Auth::user()->participante;
        if (!$participante || $tarea->participante_id !== $participante->id_participante) {
            return response()->json(['error' => 'No tienes permisos para esta tarea'], 403);
        }

        $rutaEvidencia = null;
        if ($request->hasFile('evidencia')) {
            $archivo = $request->file('evidencia');
            $nombreArchivo = 'pausa_' . time() . '_' . $archivo->getClientOriginalName();
            $rutaEvidencia = $archivo->storeAs('pausas', $nombreArchivo, 'public');
        }

        // Crear solicitud de pausa
        $pausa = Pausa::create([
            'tarea_id' => $taskId,
            'motivo' => $request->motivo,
            'hora' => now(),
            'evidencia' => $rutaEvidencia,
            'estado' => 'pendiente'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Solicitud de pausa enviada correctamente',
            'pausa' => $pausa
        ]);
    }

    /**
     * Obtener detalles de una tarea específica
     */
    public function getTaskDetails($taskId)
    {
        $participante = Auth::user()->participante;
        
        if (!$participante) {
            return response()->json(['error' => 'Participante no encontrado'], 404);
        }
        
        $tarea = Tarea::where('id_tarea', $taskId)
                     ->where('participante_id', $participante->id_participante)
                     ->with(['evidencias', 'pausas', 'proyecto'])
                     ->firstOrFail();

        return response()->json([
            'tarea' => $tarea,
            'evidencias' => $tarea->evidencias,
            'pausas' => $tarea->pausas
        ]);
    }

    /**
     * Borrar una evidencia
     */
    public function deleteEvidence(Request $request, $taskId, $evidenciaId)
    {
        $tarea = Tarea::findOrFail($taskId);
        $participante = Auth::user()->participante;
        
        // Verificar permisos
        if (!$participante || $tarea->participante_id != $participante->id_participante) {
            return response()->json(['error' => 'No tienes permisos para esta tarea'], 403);
        }

        $evidencia = Evidencia::findOrFail($evidenciaId);
        
        // Verificar que la evidencia pertenece a la tarea
        if ($evidencia->id_tarea !== $tarea->id_tarea) {
            return response()->json(['error' => 'La evidencia no pertenece a esta tarea'], 403);
        }

        // Eliminar el archivo físico
        if (Storage::disk('public')->exists($evidencia->archivo)) {
            Storage::disk('public')->delete($evidencia->archivo);
        }

        // Eliminar el registro
        $evidencia->delete();

        // Si no quedan evidencias, volver a estado ausente
        if ($tarea->evidencias()->count() === 0) {
            $tarea->update([
                'estado' => 'ausente',
                'fecha_inicio' => null,
                'fecha_fin' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Evidencia eliminada correctamente'
        ]);
    }

    /**
     * Renombrar una evidencia
     */
    public function renameEvidence(Request $request, $taskId, $evidenciaId)
    {
        $request->validate([
            'nuevo_nombre' => 'required|string|max:255'
        ]);

        $tarea = Tarea::findOrFail($taskId);
        $participante = Auth::user()->participante;
        
        // Verificar permisos
        if (!$participante || $tarea->participante_id != $participante->id_participante) {
            return response()->json(['error' => 'No tienes permisos para esta tarea'], 403);
        }

        $evidencia = Evidencia::findOrFail($evidenciaId);
        
        // Verificar que la evidencia pertenece a la tarea
        if ($evidencia->id_tarea !== $tarea->id_tarea) {
            return response()->json(['error' => 'La evidencia no pertenece a esta tarea'], 403);
        }

        // Obtener la extensión del archivo original
        $extension = pathinfo($evidencia->archivo, PATHINFO_EXTENSION);
        
        // Crear el nuevo nombre del archivo
        $nuevoNombreArchivo = time() . '_' . $request->nuevo_nombre . '.' . $extension;
        $nuevaRuta = 'evidencias/' . $nuevoNombreArchivo;

        // Renombrar el archivo físico
        if (Storage::disk('public')->exists($evidencia->archivo)) {
            Storage::disk('public')->move($evidencia->archivo, $nuevaRuta);
            
            // Actualizar el registro en la base de datos
            $evidencia->update([
                'archivo' => $nuevaRuta
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Evidencia renombrada correctamente',
            'evidencia' => $evidencia
        ]);
    }
}