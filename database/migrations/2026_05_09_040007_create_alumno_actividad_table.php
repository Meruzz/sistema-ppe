<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumno_actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();
            $table->decimal('horas_confirmadas', 5, 2)->default(0);
            $table->enum('estado', ['pendiente', 'asistio', 'falto', 'justificado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamp('confirmado_en')->nullable();
            $table->timestamps();

            $table->unique(['alumno_id', 'actividad_id']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumno_actividad');
    }
};
