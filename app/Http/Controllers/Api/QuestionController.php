<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Contracts\CountryServiceInterface;
use App\Exceptions\CouldNotGetCapitalsException;

class QuestionController extends Controller
{
    public function get(Request $request, $quizId)
    {
        $service = App::make(CountryServiceInterface::class);
        try{
            $allCountries = $service->getAllCountries($quizId);
        } catch (CouldNotGetCapitalsException $e){
            abort(500, 'Could not get capitals');
        }

        $questionCountry = $service->pickCountryForQuiz($quizId, $allCountries);
        $randomOptions = $service->pickRandomCapitals($allCountries, $questionCountry['name'], 2);

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

        return response()->json([
            'country' => $questionCountry['name'],
            'options' => $options
        ]);
    }
}
