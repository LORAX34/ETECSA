<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('llamadas', function (Blueprint $table) {
            $table->id();
            $table->integer('minutos');
            $table->dateTime('fecha_hora');
            $table->string('numero_origen', 15);
            $table->string('numero_destino', 20);
            $table->boolean('es_internacional');
            $table->boolean('es_nacional');
            $table->boolean('es_local');
            $table->boolean('es_servicio');
            $table->string('cod_local_origen', 4)->nullable();
            $table->string('cod_local_dest', 4)->nullable();
            $table->string('cod_pais_dest', 4)->nullable();
            $table->decimal('tarifa_pais', 10, 2)->nullable();
            $table->decimal('precio', 10, 2);
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->timestamps();

            $table->index('fecha_hora');
            $table->index('cliente_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('llamadas');
    }
};
