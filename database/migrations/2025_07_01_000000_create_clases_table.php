<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clases', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->foreignId('profesor_id')->constrained('profesores')->onDelete('cascade');
            $table->string('horario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};
