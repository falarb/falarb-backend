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
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->string('id', 24)->primary();
            $table->enum('status', ['analise', 'agendada', 'concluida', 'indeferida']);
            $table->string('mot_indeferimento', 255)->nullable();
            $table->text('descricao')->nullable();
            $table->date('data_agendamento')->nullable();
            $table->datetime('data_conclusao')->nullable();
            $table->string('token_solicitacao', 6)->nullable();
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->string('id_cidadao', 24);
            $table->string('id_categoria', 24);
            $table->string('id_comunidade', 24);
            $table->foreign('id_cidadao')->references('id')->on('cidadaos')->onDelete('cascade');
            $table->foreign('id_categoria')->references('id')->on('categorias')->onDelete('cascade');
            $table->foreign('id_comunidade')->references('id')->on('comunidades')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes');
    }
};
