<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->foreignId('grupo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ambito_id')->nullable()->constrained('ambitos')->nullOnDelete();
            $table->enum('fase', ['formacion', 'ejecucion', 'presentacion'])->nullable();
            $table->date('fecha');
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->decimal('horas_asignadas', 5, 2);
            $table->string('lugar')->nullable();
            $table->enum('estado', ['planificada', 'en_curso', 'completada', 'cancelada'])->default('planificada');
            $table->timestamps();

            $table->index('fecha');
            $table->index('estado');
            $table->index('fase');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
