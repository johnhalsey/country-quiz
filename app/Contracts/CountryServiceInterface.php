<?php

namespace App\Contracts;

interface CountryServiceInterface
{
    public function getCapitalsForQuiz(string $quizId, int $count = 3);
}
