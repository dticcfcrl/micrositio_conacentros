<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /* Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        }); */

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            /* $table->string('usuario')->unique(); */
            $table->string('email')->unique();
            $table->string('buzon')->unique()->nullable();
            $table->string('password');
            $table->string('no_personal')->nullable();
            $table->timestamp('email_verificado')->nullable();
            $table->string('id_estado');
            $table->string('id_ccls');
            $table->string('perfil')->default('conciliador');
            $table->string('status')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('password_changed_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
