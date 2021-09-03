<?php

namespace App\Services\WeatherServices;

abstract class AbstractWeatherAPI
{
    protected $apiURL;
    public $DataOwner;
    private $conversionTable;

    public function __construct()
    {
        $this->conversionTable = config('weather_data.WEATHER_TYPE') ?? [];
    }

    protected function parseWeatherData($weather) {
        return $this->conversionTable[strtolower(trim($weather))] ?? "n/a" ;
    }
}
