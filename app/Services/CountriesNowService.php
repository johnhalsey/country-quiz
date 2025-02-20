<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Adapters\CountriesNowAdapter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Contracts\CountryServiceInterface;
use Illuminate\Http\Client\HttpClientException;

class CountriesNowService implements CountryServiceInterface
{
    public function __construct(private CountriesNowAdapter $adapter)
    {
    }

    public function getCapitalsForQuiz(string $quizId, int $count = 3)
    {
        try{
            $response = Cache::remember('capitals-' . $quizId, Carbon::now()->addHour(), function () {
                return $this->adapter->get('capital');
            });
        } catch (HttpClientException $ex) {
            // do something here
        }

        $countries = $response->json()['data'];
        Log::info($countries);

        // filter out any countries already used in this quiz
        foreach(Session::get($quizId) as $country){
            array_filter($countries, function ($item) use ($country) {
                return $item['name'] === $country;
            });
        }
        Log::info('-------------');
        Log::info($countries);

        shuffle($countries);
        return array_slice($countries, 0, $count, true);
    }
}
