<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servicios_matutinos', function (Blueprint $table) {
            $table->id();
            $table->time('hora_programada');
            $table->string('codigo_funcion', 10);
            $table->foreignId('llamada_id')->constrained('llamadas');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicios_matutinos');
    }
};
