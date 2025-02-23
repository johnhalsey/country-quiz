<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Contracts\CountryServiceInterface;
use App\Exceptions\CouldNotGetCapitalsException;
use App\Exceptions\NoMoreCountriesForQuizException;

class QuestionController extends Controller
{
    public function get(Request $request, $quizId): JsonResponse
    {
        $service = App::make(CountryServiceInterface::class);
        try{
            $allCountries = $service->getAllCountries($quizId);
        } catch (CouldNotGetCapitalsException $e){
            abort(500, 'Could not get capitals');
        }

        try{
            $questionCountry = $service->pickCountryForQuiz($quizId, $allCountries);
        } catch (NoMoreCountriesForQuizException $e){
            // send the user to the complete page
            return response()->json([
                'redirect' => route('quiz.complete', $quizId)
            ]);
        }

        $randomOptions = $service->pickRandomCapitals($allCountries, $questionCountry['name'], 2);

        return response()->json([
            'country' => $questionCountry['name'],
            'options' => $this->formatQuestionOptions($quizId, $questionCountry, $randomOptions)
        ]);
    }

    private function formatQuestionOptions(string $quizId, array $questionCountry, array $randomOptions): array
    {
        $sessionCountries = Session::get($quizId);
        $sessionCountries[] = $questionCountry['name'];
        Session::put($quizId, $sessionCountries);

        $options = [
            [
                'capital' => $questionCountry['capital'],
                'correct' => true,
            ]
        ];

        foreach ($randomOptions as $option) {
            $options[] = [
                'capital' => $option['capital'] == '' ? 'No capital' : $option['capital'],
                'correct' => false,
            ];
        }

        shuffle($options);

        return $options;
    }
}
