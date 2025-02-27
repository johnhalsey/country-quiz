<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/quiz', [QuizController::class, 'start'])->name('quiz.start');
Route::get('quiz/{quizId}/complete', [QuizController::class, 'complete'])->name('quiz.complete');
