<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidenciasTable extends Migration
{
    public function up()
    {
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id('id_evidencia');
            $table->unsignedBigInteger('id_tarea');
            $table->string('archivo');
            $table->enum('tipo_archivo', ['pdf', 'docx', 'imagen']);
            $table->timestamp('fecha_subida');
            $table->enum('estado_validación', ['pendiente', 'aprobada', 'rechazada']);
            $table->text('observaciones_validacion')->nullable();

            $table->foreign('id_tarea')->references('id_tarea')->on('tareas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evidencias');
    }
}