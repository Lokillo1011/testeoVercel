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
        Schema::create('tipos_usuarios', function (Blueprint $table) {
            $table->id();
            //tipo_usuario: admin, user
            $table->string('tipo_usuario');
            //activo: 0 = inactivo, 1 = activo
            $table->integer('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_usuarios');
    }
};
