<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Contracts\CountryServiceInterface;
use App\Http\Requests\MakeSelectionRequest;
use App\Exceptions\CouldNotGetSingleCountryException;

class SelectionValidationController extends Controller
{
    public function __construct(private CountryServiceInterface $service)
    {
    }

    public function validate(MakeSelectionRequest $request): JsonResponse
    {
        try {
            // try and get the data for this one specirfic country
            $country = $this->service->getSingleCountry(Str::lower($request->input('country')));
        } catch (CouldNotGetSingleCountryException) {
            abort(500, 'Could not get single country');
        }

        // compare the country's capital with the captial submitted by the user
        $correct = Str::lower($country['capital']) === Str::lower($request->input('capital'));

        return response()->json([
            'correct' => $correct,
        ]);
    }
}
