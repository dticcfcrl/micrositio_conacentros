<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisponibilidadFechasOficinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas_disponibilidad_fechas_oficinas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_administrador');
            $table->integer('id_ccls');
            $table->date('fecha');
            $table->boolean('status')->default(true);
            ///$table->enum('tipo', [0,1])->nullable(); // 0:aplica a oficina, 1:aplica atennción de citas por conciliaddres
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas_disponibilidad_fechas_oficinas');
    }
}
