<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cidadaos', function (Blueprint $table) {
            $table->string('id', 24)->primary();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('telefone')->nullable();
            $table->string('cpf')->unique();
            $table->string('ultimo_codigo')->nullable();
            $table->timestamp('codigo_enviado_em')->nullable();
            $table->boolean('bloqueado')->default(false);
            $table->timestamp('bloqueado_em')->nullable();
            $table->string('bloqueado_por')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cidadaos');
    }
};
