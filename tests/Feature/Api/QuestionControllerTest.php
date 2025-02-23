<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class QuestionControllerTest extends TestCase
{
    public function test_it_will_return_county_and_options_for_quiz_id()
    {
        Cache::flush();

        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Session::put($quizId, []);

        Http::fake([
            'https://countriesnow.space/api/v0.1/countries/capital' => Http::response([
                'error' => false,
                'data'  => [
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
                    // ... and lots more
                ]
            ]),
        ]);

        $response = $this->json(
            'GET',
            'api/quiz/' . $quizId . '/question'
        )->assertStatus(200);

        $array = json_decode($response->getContent(), true);
        $this->assertCount(3, $array['options']);

        $false = 0;
        $true = 0;
        for ($i = 0; $i < count($array['options']); $i++) {
            if ($array['options'][$i]['correct']) {
                $true++;
            } else {
                $false++;
            }
        }
        $this->assertSame(2, $false);
        $this->assertSame(1, $true);
    }

    public function test_it_will_not_use_country_already_in_session()
    {
        Cache::flush();
        Carbon::setTestNow(Carbon::now());

        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Session::put($quizId, ['Afghanistan']);

        Http::fake([
            'https://countriesnow.space/api/v0.1/countries/capital' => Http::response([
                'error' => false,
                'data'  => [
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
                    // ... and lots more
                ]
            ]),
        ]);

        for ($i = 0; $i < 3; $i++) {
            $response = $this->json(
                'GET',
                'api/quiz/' . $quizId . '/question'
            )->assertStatus(200);

            $array = json_decode($response->getContent(), true);
            $this->assertCount(3, $array['options']);
            $this->assertNotSame('Afghanistan', $array['country']);
        }
    }

    public function test_it_will_add_selected_country_to_session()
    {
        Cache::flush();
        Carbon::setTestNow(Carbon::now());

        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Session::put($quizId, ['Afghanistan']);

        $this->assertCount(1, Session::get($quizId));

        Http::fake([
            'https://countriesnow.space/api/v0.1/countries/capital' => Http::response([
                'error' => false,
                'data'  => [
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
                    // ... and lots more
                ]
            ]),
        ]);

        $this->json(
            'GET',
            'api/quiz/' . $quizId . '/question'
        )->assertStatus(200);

        $this->assertCount(2, Session::get($quizId));
    }

    public function test_it_will_abort_on_http_exception()
    {
        Cache::flush();
        Carbon::setTestNow(Carbon::now());

        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Session::put($quizId, ['Afghanistan']);

        Http::fake([
            'https://countriesnow.space/api/v0.1/countries/capital' => Http::response(status: 500),
        ]);

        $response = $this->json(
            'GET',
            'api/quiz/' . $quizId . '/question'
        )->assertStatus(500)
            ->assertJsonFragment([
                'message' => 'Could not get capitals',
            ]);
    }

    public function test_it_will_abort_on_http_response_error()
    {
        Cache::flush();
        Carbon::setTestNow(Carbon::now());

        $quizId = 'quiz-' . Carbon::now()->timestamp;

        Session::put($quizId, ['Afghanistan']);

        Http::fake([
            'https://countriesnow.space/api/v0.1/countries/capital' => Http::response([
                'error' => true,
            ], status: 200),
        ]);

        $this->json(
            'GET',
            'api/quiz/' . $quizId . '/question'
        )->assertStatus(500)
            ->assertJsonFragment([
                'message' => 'Could not get capitals',
            ]);
    }

    public function test_it_will_return_redirect_url_if_there_are_no_more_countries_in_quiz()
    {
        Cache::flush();

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

        Http::fake([
            'https://countriesnow.space/api/v0.1/countries/capital' => Http::response([
                'error' => false,
                'data'  => [
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
                    // ... and lots more
                ]
            ]),
        ]);

        $response = $this->json(
            'GET',
            'api/quiz/' . $quizId . '/question'
        )->assertStatus(200)
            ->assertJsonFragment([
                'redirect' => route('quiz.complete', $quizId)
            ]);
    }
}
