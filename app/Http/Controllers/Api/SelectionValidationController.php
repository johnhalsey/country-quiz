<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Contracts\CountryServiceInterface;
use App\Http\Requests\MakeSelectionRequestion;
use App\Exceptions\CouldNotGetSingleCountryException;

class SelectionValidationController extends Controller
{
    public function __construct(private CountryServiceInterface $service)
    {
    }

    public function validate(MakeSelectionRequestion $request): JsonResponse
    {
        try {
            $country = $this->service->getSingleCountry(Str::lower($request->input('country')));
        } catch (CouldNotGetSingleCountryException $ex) {
            abort(500, 'Could not get single country');
        }

        $correct = Str::lower($country['capital']) === Str::lower($request->input('capital'));

        return response()->json([
            'correct' => $correct,
        ]);
    }
}
