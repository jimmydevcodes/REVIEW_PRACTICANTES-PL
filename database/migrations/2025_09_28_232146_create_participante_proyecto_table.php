<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipanteProyectoTable extends Migration
{
    public function up()
    {
        Schema::create('participante_proyecto', function (Blueprint $table) {
            $table->id('id_participante_proyecto');
            
            // ✅ CORREGIDO: debe ser unsignedBigInteger, NO string
            $table->unsignedBigInteger('id_participante');
            $table->unsignedBigInteger('id_proyecto');
            
            $table->string('rol_en_proyecto')->nullable();
            $table->timestamp('fecha_asignacion')->useCurrent();

            $table->foreign('id_participante')
                  ->references('id_participante')
                  ->on('participantes')
                  ->onDelete('cascade');
                  
            $table->foreign('id_proyecto')
                  ->references('id_proyecto')
                  ->on('proyectos')
                  ->onDelete('cascade');
            
            // Evitar registros duplicados
            $table->unique(['id_participante', 'id_proyecto']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('participante_proyecto');
    }
}