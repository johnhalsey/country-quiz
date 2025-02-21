<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Contracts\CountryServiceInterface;

class QuestionController extends Controller
{
    public function get(Request $request, $quizId)
    {
        $service = App::make(CountryServiceInterface::class);
        $capitals = $service->getCapitalsForQuiz($quizId);

        $country = $capitals[0];
        $options = [
            [
                'capital' => $capitals[0]['capital'],
                'correct' => true,
            ],
            [
                'capital' => $capitals[1]['capital'],
                'correct' => false,
            ],
            [
                'capital' => $capitals[2]['capital'],
                'correct' => false,
            ]
        ];

        shuffle($options);

        return response()->json([
            'country' => $country['name'],
            'options' => $options
        ]);
    }
}
