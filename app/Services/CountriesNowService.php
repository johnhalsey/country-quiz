<?php

namespace App\Services;

use Carbon\Carbon;
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
            $countries = Cache::remember('capitals-' . $quizId, Carbon::now()->addHour(), function () {
                $response = $this->adapter->get('capital');
                return $response->json()['data'];
            });
        } catch (HttpClientException $ex) {
            // @TODO throw exception here
        }

        // filter out any countries already used in this quiz
        foreach(Session::get($quizId) as $country){
            array_filter($countries, function ($item) use ($country) {
                return $item['name'] === $country;
            });
        }

        shuffle($countries);
        return array_slice($countries, 0, $count, true);
    }
}
