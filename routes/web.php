<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RelatorioController;

Route::get('/', function () {
    return view('welcome');
});

// Rota para o relatório HTML
Route::get('/relatorio', function () {
    return view('relatorios.geral', [
        'dados' => [],
        'dataEscrita' => 'Último ano',
        'categoria' => 'todas',
        'comunidade' => 'todas',
    ]);
});

// Rota para o relatório PDF
Route::get('/relatorio/pdf', [RelatorioController::class, 'geral'])->name('relatorio.pdf');

// Rota para e-mail com o token de validação
Route::get('/confirma-email', function () {
    return view('emails.token_valida_email', [
        'userName' => 'Fulano de Tal',
        'token' => 'AB12'
    ]);
});
