<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('nombre_cliente');
            $table->string('ruc')->nullable();
            $table->string('dirección')->nullable();
            $table->string('correo_contacto')->nullable();
            $table->string('teléfono')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}