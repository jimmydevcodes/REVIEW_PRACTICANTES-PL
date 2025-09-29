<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePausasTable extends Migration
{
    public function up()
    {
        Schema::create('pausas', function (Blueprint $table) {
            $table->id('id_pausa');
            $table->unsignedBigInteger('tarea_id');
            $table->text('motivo');
            $table->timestamp('hora');
            $table->string('evidencia')->nullable();
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada']);

            $table->foreign('tarea_id')->references('id_tarea')->on('tareas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pausas');
    }
}