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
            $table->string('codigo_clave')->unique(); // ← AGREGAR ESTA LÍNEA
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->unsignedBigInteger('creado_por');
            
            // Foreign key para id_area
            $table->unsignedBigInteger('id_area');
            $table->foreign('id_area')
                  ->references('id_area')
                  ->on('areas')
                  ->onDelete('cascade');
            
            // Foreign key para creado_por (si tienes tabla users)
            $table->foreign('creado_por')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupos');
    }
}