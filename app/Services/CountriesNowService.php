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
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        try {
            $response = Cache::remember('capitals-' . $quizId, Carbon::now()->addHour(), function () {
                $response = $this->adapter->get('capital');
                return $response->json();
            });
        } catch (RequestException $ex) {
            // putting this log here on purpose to look back on, if it fails.
            Log::error($ex->getMessage());
            throw new CouldNotGetCapitalsException($ex->getMessage());
        }

        if ($response['error']) {
            throw new CouldNotGetCapitalsException();
        }

        return $response['data'];
    }

    public function pickCountryForQuiz(string $quizId, array $countries): array
    {
        // filter out any countries already used in this quiz
        if (Session::has($quizId)) {
            for ($i = 0; $i < count($countries); $i++) {
                if (in_array($countries[$i]['name'], Session::get($quizId))) {
                    unset($countries[$i]);
                }
            }
        }

        shuffle($countries);
        return $countries[0];
    }

    public function pickRandomCapitals(array $cuntries, string $exludingCountry, int $count = 2): array
    {
        $filteredCountries = array_filter($cuntries, function ($item) use ($exludingCountry) {
            return $item['name'] !== $exludingCountry;
        });

        shuffle($filteredCountries);
        return array_slice($filteredCountries, 0, $count, true);
    }

}
