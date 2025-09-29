<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantesTable extends Migration
{
    public function up()
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->id('id_participante');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->nullable();
            $table->string('teléfono')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedBigInteger('id_área')->nullable();
            $table->unsignedBigInteger('id_cargo')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('participantes');
    }
}