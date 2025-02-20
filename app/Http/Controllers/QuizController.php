<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class QuizController extends Controller
{
    public function start()
    {
        // start a session
        $id = 'quiz-' . Carbon::now()->timestamp;
        Session::put($id, []);
        return Inertia::render('Quiz', [
            'quizId' => $id,
        ]);
    }
}
