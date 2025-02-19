<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');

require __DIR__ . '/api.php';
