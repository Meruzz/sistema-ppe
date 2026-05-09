<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ambitos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('codigo', 20)->nullable()->unique();
            $table->text('descripcion')->nullable();
            $table->string('color', 20)->default('blue');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambitos');
    }
};
