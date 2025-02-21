<?php

namespace App\Adapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CountriesNowAdapter
{
    protected $baseUri = 'https://countriesnow.space/api/v0.1/countries/';

    /**
     * @param $url
     * @return Response
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function get($url): Response
    {
        return Http::get($this->baseUri . $url)->throw();
    }
}
