<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('usuario_nombre')->nullable();
            $table->string('accion'); 
            $table->string('url')->nullable();
            $table->string('metodo', 10)->nullable(); 
            $table->string('ip', 45)->nullable();
            $table->text('detalles_query')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_logs');
    }
};
