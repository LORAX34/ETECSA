<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagos_mensuales', function (Blueprint $table) {
            $table->id();
            $table->integer('mes');
            $table->integer('año');
            $table->decimal('total_llamadas', 10, 2);
            $table->decimal('total_servicios', 10, 2);
            $table->decimal('total', 10, 2);
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->timestamps();

            $table->index(['cliente_id', 'mes', 'año']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos_mensuales');
    }
};
