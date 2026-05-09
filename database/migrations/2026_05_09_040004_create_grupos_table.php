<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('docente_id')->nullable()->constrained('docentes')->nullOnDelete();
            $table->foreignId('ambito_id')->nullable()->constrained('ambitos')->nullOnDelete();
            $table->foreignId('anio_lectivo_id')->nullable()->constrained('anio_lectivos')->nullOnDelete();
            $table->enum('anio_bachillerato', ['1ro', '2do'])->default('1ro');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('anio_lectivo_id');
            $table->index('anio_bachillerato');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
