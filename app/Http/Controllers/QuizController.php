<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Session;

class QuizController extends Controller
{
    public function start(): Response
    {
        // start a session
        $id = 'quiz-' . Carbon::now()->timestamp;
        Session::put($id, []);
        return Inertia::render('Quiz', [
            'quizId' => $id,
        ]);
    }

    public function complete(string $quizId): Response
    {
        Session::remove($quizId);
        return Inertia::render('Complete');
    }
}
