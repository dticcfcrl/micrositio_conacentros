<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasRegistradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas_registradas', function (Blueprint $table) {
            $table->id();
            $table->string('cita_folio')->unique();
            $table->integer('id_estado');
            $table->integer('id_ccls');
            $table->char('correo', 100);
            $table->char('celular', 10)->nullable();
            $table->date('cita_fecha');
            $table->time('cita_hora');
            $table->string('nombre',100);
            $table->string('apellidos',100);
            $table->string('observaciones', 255);
            $table->boolean('status')->default(true);
            $table->integer('id_conciliador');
            $table->enum('status_conciliador',[0,1,2])->default(2); //0:cita cancelada, 1:cita atendida, 2: cita pendiente por atender
            $table->string('observaciones_conciliador', 255)->nullable();
            $table->integer('id_configuracion')->default(0);
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
        Schema::dropIfExists('citas_registradas');
    }
}
