<?php

namespace App\Contracts;

use App\Exceptions\CouldNotGetCapitalsException;

interface CountryServiceInterface
{
    /**
     * @param string $quizId
     * @return array
     * @throws CouldNotGetCapitalsException
     */
    public function getAllCountries(string $quizId): array;

    public function pickCountryForQuiz(string $quizId, array $countries): array;

    public function pickRandomCapitals(array $countries, string $exludingCountry, int $count = 2): array;
}
