<?php

use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CidadaoController;
use App\Http\Controllers\ComunidadeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModificacaoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\SolicitacaoController;
use Illuminate\Support\Facades\Route;

// Autenticação Painel Administrador
Route::post('/login', [AuthController::class, 'loginAdministrador']);
Route::post('/esqueci-senha', [AuthController::class, 'esqueciSenha']);

// ---> ROTAS PUBLICAS <---
// Comunidades
Route::prefix('comunidades')->group(function () {
    Route::get('/', [ComunidadeController::class, 'listar']);
});

// Cidadãos
Route::prefix('cidadaos')->group(function () {
    Route::post('/', [CidadaoController::class, 'criar']);
    Route::post('/envia-token/{id}', [CidadaoController::class, 'enviaToken']);
    Route::post('/verifica-email/{id}', [CidadaoController::class, 'verificaEmail']);
    Route::get('/email-existe', [CidadaoController::class, 'emailExiste']);
    Route::put('/atualiza-email/{id}', [CidadaoController::class, 'atualizaEmail']);
});

// Solicitações
Route::prefix('solicitacoes')->group(function () {
    Route::post('/', [SolicitacaoController::class, 'criar']);
    Route::get('/busca-por-token/{token}', [SolicitacaoController::class, 'buscaPorToken']);
});

// Categorias
Route::prefix('categorias')->group(function () {
    Route::get('/', [CategoriaController::class, 'listar']);
});

// ---> ROTAS PROTEGIDAS <---
Route::middleware(['auth:sanctum'])->group(function () {
    // Administradores
    Route::post('/logout', [AuthController::class, 'logoutAdministrador']);
    Route::prefix('administradores')->group(function () {
        Route::get('/', [AdministradorController::class, 'listar']);
        Route::get('/{id}', [AdministradorController::class, 'visualizar']);
        Route::post('/', [AdministradorController::class, 'criar']);
        Route::put('/{id}', [AdministradorController::class, 'atualizar']);
        Route::delete('/{id}', [AdministradorController::class, 'excluir']);
    });

    // Categorias
    Route::prefix('categorias')->group(function () {
        Route::post('/', [CategoriaController::class, 'criar']);
        Route::get('/{id}', [CategoriaController::class, 'visualizar']);
        Route::put('/{id}', [CategoriaController::class, 'atualizar']);
        Route::delete('/{id}', [CategoriaController::class, 'excluir']);
    });

    // Comunidades
    Route::prefix('comunidades')->group(function () {
        Route::get('/{id}', [ComunidadeController::class, 'visualizar']);
        Route::post('/', [ComunidadeController::class, 'criar']);
        Route::put('/{id}', [ComunidadeController::class, 'atualizar']);
        Route::delete('/{id}', [ComunidadeController::class, 'excluir']);
    });

    // Cidadãos
    Route::prefix('cidadaos')->group(function () {
        Route::get('/', [CidadaoController::class, 'listar']);
        Route::get('/{id}', [CidadaoController::class, 'visualizar']);
        Route::put('/{id}', [CidadaoController::class, 'atualizar']);
        Route::delete('/{id}', [CidadaoController::class, 'excluir']);
    });

    // Modificações
    Route::prefix('modificacoes')->group(function () {
        Route::get('/', [ModificacaoController::class, 'index']);
        Route::post('/', [ModificacaoController::class, 'store']);
        Route::get('/{id}', [ModificacaoController::class, 'show']);
        Route::put('/{id}', [ModificacaoController::class, 'update']);
        Route::delete('/{id}', [ModificacaoController::class, 'destroy']);
    });

    // Solicitações
    Route::prefix('solicitacoes')->group(function () {
        Route::get('/', [SolicitacaoController::class, 'listar']);
        Route::get('/{id}', [SolicitacaoController::class, 'visualizar']);
        Route::put('/{id}', [SolicitacaoController::class, 'atualizar']);
        Route::delete('/{id}', [SolicitacaoController::class, 'excluir']);
    });

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/indicadores', [DashboardController::class, 'indicadores']);
    });

    // Relatórios
    Route::prefix('relatorios')->group(function () {
        Route::get('/geral', [RelatorioController::class, 'geral']);
    });
});