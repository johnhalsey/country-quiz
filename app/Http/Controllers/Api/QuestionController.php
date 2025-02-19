<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function get(Request $request, $quizId)
    {
        // get existing countries from session with quiz id
        // get 3 countries in random order, where not already chosen
        // construct country, and 3 possible answers
        // send back as json
    }
}
