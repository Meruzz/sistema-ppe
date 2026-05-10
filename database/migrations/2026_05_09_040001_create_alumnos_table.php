<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('cedula', 10)->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->enum('anio_bachillerato', ['1ro', '2do', '3ro']);
            $table->string('paralelo', 1)->nullable();
            $table->string('representante')->nullable();
            $table->string('telefono_representante', 20)->nullable();
            $table->boolean('activo')->default(true);

            // Flags para evitar reenvíos de notificaciones
            $table->boolean('notif_50_enviada')->default(false);
            $table->boolean('notif_80_enviada')->default(false);
            $table->boolean('notif_100_enviada')->default(false);
            $table->timestamp('notif_nota_baja_en')->nullable();

            $table->timestamps();

            $table->index(['anio_bachillerato', 'paralelo']);
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
