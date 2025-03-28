<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionOficinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas_configuracion_oficinas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_administrador');
            $table->integer('id_ccls');
            $table->integer('total_conciliadores')->default(0);
            $table->integer('total_auxiliares')->default(0);
            $table->boolean('status_lunes')->default(true);
            $table->boolean('status_martes')->default(true);
            $table->boolean('status_miercoles')->default(true);
            $table->boolean('status_jueves')->default(true);
            $table->boolean('status_viernes')->default(true);
            $table->time('hora_cita_inicio')->default('08:00:00');
            $table->time('hora_cita_fin')->default('18:00:00');
            $table->tinyInteger('minutos_cita')->default(15);
            $table->tinyInteger('meses_cita')->default(1);
            $table->time('hora_comida_inicio')->default('14:00:00');
            $table->time('hora_comida_fin')->default('15:00:00');
            $table->time('status')->default(true);
            $table->date('aplica')->default(now());
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
        Schema::dropIfExists('citas_configuracion_oficinas');
    }
}
