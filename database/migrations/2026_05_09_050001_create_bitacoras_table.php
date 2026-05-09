<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('actividad_id')->nullable()->constrained('actividades')->nullOnDelete();
            $table->date('fecha');
            $table->text('contenido');
            $table->text('aprendizajes')->nullable();
            $table->decimal('calificacion', 4, 2)->nullable();
            $table->text('observaciones_docente')->nullable();
            $table->foreignId('revisado_por_docente_id')->nullable()->constrained('docentes')->nullOnDelete();
            $table->timestamp('revisado_en')->nullable();
            $table->timestamps();

            $table->unique(['alumno_id', 'actividad_id']);
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
