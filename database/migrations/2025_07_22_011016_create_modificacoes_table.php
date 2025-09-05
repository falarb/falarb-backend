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
            $table->text('valor_novo');
            $table->text('valor_anterior');
            $table->timestamp('gerado_em')->useCurrent();
            $table->enum('tipo', ['status', 'descricao', 'data_agendamento', 'categoria', 'outros']);
            $table->string('id_administrador', 24);
            $table->string('id_solicitacao', 24);
            $table->foreign('id_administrador')->references('id')->on('administradors')->onDelete('cascade');
            $table->foreign('id_solicitacao')->references('id')->on('solicitacoes')->onDelete('cascade');
            $table->timestamps();
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
