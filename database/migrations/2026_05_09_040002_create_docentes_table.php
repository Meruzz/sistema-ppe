<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('cedula', 10)->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('especialidad')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('es_coordinador')->default(false);
            $table->timestamps();

            $table->index('activo');
            $table->index('es_coordinador');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
