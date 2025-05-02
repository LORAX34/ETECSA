<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paises', function (Blueprint $table) {
            $table->string('codigo_pais', 4)->primary();
            $table->string('nombre', 50);
            $table->decimal('tarifa', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paises');
    }
};
