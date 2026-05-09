<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anio_lectivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 9)->unique();        // e.g. "2025-2026"
            $table->enum('ciclo', ['sierra', 'costa'])->default('sierra');
            $table->date('inicio')->nullable();
            $table->date('fin')->nullable();
            $table->boolean('activo')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anio_lectivos');
    }
};
