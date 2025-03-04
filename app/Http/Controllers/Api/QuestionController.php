<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Contracts\CountryServiceInterface;
use App\Exceptions\CouldNotGetCapitalsException;
use App\Exceptions\NoMoreCountriesForQuizException;

class QuestionController extends Controller
{
    public function __construct(private CountryServiceInterface $service)
    {
    }

    public function get(Request $request, $quizId): JsonResponse
    {
        try{
            // try and get all the countries from the service
            $allCountries = $this->service->getAllCountries($quizId);
        } catch (CouldNotGetCapitalsException $e){
            abort(500, 'Could not get capitals');
        }

        try{
            // get one country for the next question, that has not already been used before.
            $questionCountry = $this->service->pickCountryForQuiz($quizId, $allCountries);
        } catch (NoMoreCountriesForQuizException){
            // send the user to the complete page
            return response()->json([
                'redirect' => route('quiz.complete', $quizId)
            ]);
        }

        // choose 2 other random countries to show their capitals as options
        $randomOptions = $this->service->pickRandomCapitals($allCountries, $questionCountry['name'], 2);

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
            ]
        ];

        foreach ($randomOptions as $option) {
            $options[] = [
                'capital' => $option['capital'] == '' ? 'No capital' : $option['capital'],
            ];
        }

        shuffle($options);

        return $options;
    }
}
