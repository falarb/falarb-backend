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
        Schema::create('modificacoes', function (Blueprint $table) {
            $table->string('id', 24)->primary();
            $table->json('valor_novo');
            $table->json('valor_anterior');
            $table->timestamp('gerado_em')->useCurrent();
            $table->enum('tipo', ['solicitacao', 'categoria', 'cidadao', 'comunidade', 'administrador', 'outros']);
            $table->string('id_administrador', 24);
            $table->foreign('id_administrador')->references('id')->on('administradors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modificacoes');
    }
};
