<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id('id_tarea');
            $table->unsignedBigInteger('id_proyecto');
            $table->string('nombre_tarea');
            $table->text('descripción')->nullable();
            $table->enum('estado', ['ausente', 'pendiente', 'completado', 'incompleto']);
            $table->timestamp('fecha_asignación');
            $table->timestamp('fecha_inicio_asignada');  // Fecha que debe comenzar según admin
            $table->timestamp('fecha_fin_asignada');     // Fecha límite según admin
            $table->timestamp('fecha_inicio')->nullable(); // Fecha real cuando subió primera evidencia
            $table->timestamp('fecha_fin')->nullable();    // Fecha real de última modificación
            $table->timestamp('ultima_actividad')->nullable(); // Registro de la última acción (subida o borrado)
            $table->unsignedBigInteger('participante_id');
            $table->enum('prioridad', ['alto', 'medio', 'bajo']);
            $table->enum('estado_asistencia', [
                'registro salida anticipada', 
                'registro salida tardía', 
                'incompleto'
            ])->nullable();
            $table->timestamp('grupo_fecha_inicio')->nullable(); // Fecha inicio general del grupo de tareas
            $table->timestamp('grupo_fecha_fin')->nullable();    // Fecha fin general del grupo de tareas

            $table->foreign('id_proyecto')->references('id_proyecto')->on('proyectos');
            $table->foreign('participante_id')->references('id_participante')->on('participantes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tareas');
    }
}