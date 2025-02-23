<?php

namespace Tests\Unit\Services;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use App\Services\CountriesNowService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Exceptions\CouldNotGetCapitalsException;
use App\Exceptions\NoMoreCountriesForQuizException;

class CountriesNowTest extends TestCase
{
    public function test_it_will_call_api_when_getting_all_countries()
    {
        Carbon::setTestNow(now());
        Cache::flush();
        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Http::fake([
            '*' => Http::response([
                'error' => false,
                'data' => []
            ])
        ]);

        $service = App::make(CountriesNowService::class);
        $countries = $service->getAllCountries($quizId);

        Http::assertSent(function ($request) {
            return $request->url() == 'https://countriesnow.space/api/v0.1/countries/capital';
        });
    }

    public function test_it_will_return_value_from_cache_when_getting_all_countries()
    {
        Carbon::setTestNow(now());
        Cache::flush();
        $quizId = 'quiz-' . Carbon::now()->timestamp;
        Cache::set('capitals-' . $quizId, [
            'error' => false,
            'data' => []
        ]);

        Http::fake();

        $service = App::make(CountriesNowService::class);
        $service->getAllCountries($quizId);

        Http::assertNothingSent();
    }

    public function test_it_will_throw_exception_if_http_error()
    {
        $this->expectException(CouldNotGetCapitalsException::class);

        Carbon::setTestNow(now());
        Cache::flush();
        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Http::fake([
            '*' => Http::response([], 500)
        ]);

        $service = App::make(CountriesNowService::class);
        $countries = $service->getAllCountries($quizId);
    }

    public function test_it_will_throw_exception_on_response_error()
    {
        $this->expectException(CouldNotGetCapitalsException::class);

        Carbon::setTestNow(now());
        Cache::flush();
        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Http::fake([
            '*' => Http::response([
                'error' => true,
            ], 500)
        ]);

        $service = App::make(CountriesNowService::class);
        $countries = $service->getAllCountries($quizId);
    }

    public function test_it_will_not_cache_api_response_on_error()
    {
        $this->expectException(CouldNotGetCapitalsException::class);

        Carbon::setTestNow(now());
        Cache::flush();
        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Http::fake([
            '*' => Http::response([], 500)
        ]);

        $service = App::make(CountriesNowService::class);
        $service->getAllCountries($quizId);

        $this->assertFalse(Cache::has('capitals-' . $quizId));
    }

    public function test_it_pick_country_for_quiz()
    {
        Cache::flush();
        Carbon::setTestNow(Carbon::now());

        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Session::put($quizId, ['Afghanistan', 'Aland Islands']);

        $countries = [
            [
                "name"    => "Afghanistan",
                "capital" => "Kabul",
                "iso2"    => "AF",
                "iso3"    => "AFG"
            ],
            [
                "name"    => "Aland Islands",
                "capital" => "Mariehamn",
                "iso2"    => "AX",
                "iso3"    => "ALA"
            ],
            [
                "name"    => "Albania",
                "capital" => "Tirana",
                "iso2"    => "AL",
                "iso3"    => "ALB"
            ],
        ];

        $service = App::make(CountriesNowService::class);
        $country = $service->pickCountryForQuiz($quizId, $countries);
        $this->assertSame($countries[2], $country);
    }

    public function test_it_will_pick_random_capitals()
    {
        $countries = [
            [
                "name"    => "Afghanistan",
                "capital" => "Kabul",
                "iso2"    => "AF",
                "iso3"    => "AFG"
            ],
            [
                "name"    => "Aland Islands",
                "capital" => "Mariehamn",
                "iso2"    => "AX",
                "iso3"    => "ALA"
            ],
            [
                "name"    => "Albania",
                "capital" => "Tirana",
                "iso2"    => "AL",
                "iso3"    => "ALB"
            ],
            [
                "name"    => "Algeria",
                "capital" => "Algiers",
                "iso2"    => "DZ",
                "iso3"    => "DZA"
            ],
            [
                "name"    => "Andorra",
                "capital" => "Andorra la Vella",
                "iso2"    => "AD",
                "iso3"    => "AND"
            ],
            [
                "name"    => "Angola",
                "capital" => "Luanda",
                "iso2"    => "AO",
                "iso3"    => "AGO"
            ],
            [
                "name"    => "Anguilla",
                "capital" => "The Valley",
                "iso2"    => "AI",
                "iso3"    => "AIA"
            ],
        ];

        $service = App::make(CountriesNowService::class);
        $counties = $service->pickRandomCapitals($countries, 'Andorra', 2);
        $this->assertTrue(!in_array('Andorra', $counties));
        $this->assertCount(2, $counties);

        $counties = $service->pickRandomCapitals($countries, 'Andorra', 3);
        $this->assertTrue(!in_array('Andorra', $counties));
        $this->assertCount(3, $counties);

        $counties = $service->pickRandomCapitals($countries, 'Andorra', 6);
        $this->assertTrue(!in_array('Andorra', $counties));
        $this->assertCount(6, $counties);
    }

    public function test_it_will_throw_excdption_when_picking_country_if_no_countries_left()
    {
        $this->expectException(NoMoreCountriesForQuizException::class);

        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Session::put($quizId, [
                'Afghanistan',
                'Aland Islands',
                'Albania',
                'Algeria',
                'Andorra',
                'Angola',
                'Anguilla',
            ]
        );

        $countries = [
            [
                "name"    => "Afghanistan",
                "capital" => "Kabul",
                "iso2"    => "AF",
                "iso3"    => "AFG"
            ],
            [
                "name"    => "Aland Islands",
                "capital" => "Mariehamn",
                "iso2"    => "AX",
                "iso3"    => "ALA"
            ],
            [
                "name"    => "Albania",
                "capital" => "Tirana",
                "iso2"    => "AL",
                "iso3"    => "ALB"
            ],
            [
                "name"    => "Algeria",
                "capital" => "Algiers",
                "iso2"    => "DZ",
                "iso3"    => "DZA"
            ],
            [
                "name"    => "Andorra",
                "capital" => "Andorra la Vella",
                "iso2"    => "AD",
                "iso3"    => "AND"
            ],
            [
                "name"    => "Angola",
                "capital" => "Luanda",
                "iso2"    => "AO",
                "iso3"    => "AGO"
            ],
            [
                "name"    => "Anguilla",
                "capital" => "The Valley",
                "iso2"    => "AI",
                "iso3"    => "AIA"
            ],
        ];

        $service = App::make(CountriesNowService::class);
        $service->pickCountryForQuiz($quizId, $countries);
    }
}
