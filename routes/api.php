<?php

use App\Http\Controllers\Api\QuestionController;

Route::prefix('api')->group(function () {
    Route::get('quiz/{quizId}/question', [QuestionController::class, 'get']);
});
