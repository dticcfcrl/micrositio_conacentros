<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisponibilidadOficinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas_disponibilidad_oficinas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_administrador');
            $table->integer('id_ccls');            
            $table->time('horario');
            $table->boolean('status')->default(true);
            $table->date('aplica')->default(now());
            $table->timestamps();
            //$table->foreign('id_conciliador')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas_disponibilidad_oficinas');
    }
}
