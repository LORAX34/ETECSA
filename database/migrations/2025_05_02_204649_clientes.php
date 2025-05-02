<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('carnet', 20)->unique();
            $table->string('direccion', 200);
            $table->string('telefono', 15)->unique();
            $table->enum('tipo', ['RESIDENCIAL', 'ESTATAL']);
            $table->boolean('activo')->default(true);
            $table->boolean('moroso')->default(false);
            $table->boolean('matutino')->default(false);
            $table->boolean('rastreo')->default(false);
            $table->boolean('linea_arrendada')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
