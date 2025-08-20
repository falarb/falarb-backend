<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RelatorioController;

Route::get('/', function () {
    return view('welcome');
});

// Rota para o relatório HTML
Route::get('/relatorio', function () {
    return view('relatorios.geral');
});

// Rota para o relatório PDF
Route::get('/relatorio/pdf', [RelatorioController::class, 'geral'])->name('relatorio.pdf');
