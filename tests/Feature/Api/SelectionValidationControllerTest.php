<?php

namespace Tests\Feature\Api;

use Mockery\MockInterface;
use App\Contracts\CountryServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Exceptions\CouldNotGetSingleCountryException;

class SelectionValidationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_will_fail_if_request_data_empty()
    {
        $this->json(
            'POST',
            route('api.selection.validate'),
            []
        )->assertStatus(422)
            ->assertJsonValidationErrorFor('country')
            ->assertJsonValidationErrorFor('capital');
    }

    public function test_validation_will_fail_if_request_data_invalid()
    {
        $this->json(
            'POST',
            route('api.selection.validate'),
            [
                'country' => 31321321,
                'capital' => 'some valid string' // valid
            ]
        )->assertStatus(422)
            ->assertJsonValidationErrorFor('country');

        $this->json(
            'POST',
            route('api.selection.validate'),
            [
                'country' => 'I am a country string', // valid
                'capital' => 123123123 // invalid
            ]
        )->assertStatus(422)
            ->assertJsonValidationErrorFor('capital');
    }

    public function test_it_will_abort_if_service__throws_an_error()
    {
        $mock = $this->mock(CountryServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getSingleCountry')
                ->once()
                ->andThrow(new CouldNotGetSingleCountryException());
        });

        $this->json(
            'POST',
            route('api.selection.validate'),
            [
                'country' => 'Country',
                'capital' => 'Captial'
            ]
        )->assertStatus(500)
            ->assertJsonFragment([
                'message' => 'Could not get single country'
            ]);
    }

    public function test_it_will_return_correct_true_if_request_capital_matches_response_captial()
    {
        $mock = $this->mock(CountryServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getSingleCountry')
                ->once()
                ->andReturn([
                    'name'    => 'United Kingdom',
                    'capital' => 'London',
                ]);
        });

        $this->json(
            'POST',
            route('api.selection.validate'),
            [
                'country' => 'Unitied Kingdom',
                'capital' => 'london'
            ]
        )->assertStatus(200)
            ->assertJsonFragment([
                'correct' => true
            ]);
    }

    public function test_it_will_return_correct_false_if_request_capital_doesnt_matches_response_captial()
    {
        $mock = $this->mock(CountryServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getSingleCountry')
                ->once()
                ->andReturn([
                    'name'    => 'United Kingdom',
                    'capital' => 'London',
                ]);
        });

        $this->json(
            'POST',
            route('api.selection.validate'),
            [
                'country' => 'Unitied Kingdom',
                'capital' => 'paris'
            ]
        )->assertStatus(200)
            ->assertJsonFragment([
                'correct' => false
            ]);
    }
}
