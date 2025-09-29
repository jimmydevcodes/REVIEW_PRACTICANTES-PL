<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectosTable extends Migration
{
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id('id_proyecto');
            $table->string('nombre_proyecto');
            $table->text('descripción')->nullable();
            $table->enum('prioridad', ['alto', 'medio', 'bajo']);
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin');
            $table->string('Estado');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_grupo');

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_grupo')->references('id_grupo')->on('grupos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('proyectos');
    }
}