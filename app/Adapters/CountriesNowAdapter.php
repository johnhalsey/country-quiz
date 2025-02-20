<?php

namespace App\Adapters;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CountriesNowAdapter
{
    protected $baseUri = 'https://countriesnow.space/api/v0.1/countries/';

    public function get($url)
    {
        return Http::get($this->baseUri . $url)->throw();
    }
}
