<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Adapters\CountriesNowAdapter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Contracts\CountryServiceInterface;
use Illuminate\Http\Client\RequestException;
use App\Exceptions\CouldNotGetCapitalsException;

class CountriesNowService implements CountryServiceInterface
{
    public function __construct(private CountriesNowAdapter $adapter)
    {
    }

    /**
     * @param string $quizId
     * @return array
     * @throws CouldNotGetCapitalsException
     */
    public function getAllCountries(string $quizId): array
    {
        if (Cache::has('capitals-' . $quizId)) {
            $body = Cache::get('capitals-' . $quizId);
        } else {
            try {
                $response = $this->adapter->get('capital');
                $body = $response->json();
            } catch (RequestException $ex) {
                // putting this log here on purpose to look back on, if it fails.
                Log::error($ex->getMessage());
                throw new CouldNotGetCapitalsException($ex->getMessage());
            }
        }

        if ($body['error']) {
            throw new CouldNotGetCapitalsException();
        }

        // the request was good, lets cache it now for an hour
        Cache::put('capitals-' . $quizId, $body, Carbon::now()->addHour());

        return $body['data'];
    }

    public function pickCountryForQuiz(string $quizId, array $countries): array
    {
        // filter out any countries already used in this quiz
        if (Session::has($quizId)) {
            for ($i = 0; $i < count($countries); $i++) {
                if (in_array($countries[$i]['name'], Session::get($quizId))) {
                    unset($countries[$i]);
                    continue;
                }
                if ($countries[$i]['capital'] == '') {
                    unset($countries[$i]);
                }
            }
        }

        shuffle($countries);
        return $countries[0];
    }

    public function pickRandomCapitals(array $cuntries, string $exludingCountry, int $count = 2): array
    {
        for ($i = 0; $i < count($cuntries); $i++) {
            if ($cuntries[$i]['name'] == $exludingCountry) {
                unset($cuntries[$i]);
                break;
            }

        }

        shuffle($cuntries);
        return array_slice($cuntries, 0, $count, true);
    }

}
