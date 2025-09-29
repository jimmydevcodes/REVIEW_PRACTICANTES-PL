<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacionesTable extends Migration
{
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->unsignedBigInteger('participante_id');
            $table->unsignedBigInteger('tarea_id')->nullable();
            $table->string('tipo');  // 'tarea_rechazada', 'requiere_cambios', 'tarea_aprobada', etc.
            $table->string('titulo');
            $table->text('mensaje');
            $table->json('datos_adicionales')->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_creacion');
            $table->timestamp('fecha_lectura')->nullable();

            $table->foreign('participante_id')->references('id_participante')->on('participantes');
            $table->foreign('tarea_id')->references('id_tarea')->on('tareas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notificaciones');
    }
}