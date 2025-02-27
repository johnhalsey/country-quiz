<?php

use App\Http\Controllers\Api\QuestionController;

Route::get('quiz/{quizId}/question', [QuestionController::class, 'get'])
    ->name('api.quiz.question');

Route::post('selection/validate',
    [\App\Http\Controllers\Api\SelectionValidationController::class, 'validate'])
    ->name('api.selection.validate');
