<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGruposTable extends Migration
{
    public function up()
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id('id_grupo');
            $table->string('nombre_grupo');
            $table->text('descripcion')->nullable();
            $table->string('codigo_clave')->unique();
            $table->timestamp('fecha_creacion');
            $table->string('creado_por');

            $table->foreign('creado_por')->references('id_participante')->on('participantes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupos');
    }
}