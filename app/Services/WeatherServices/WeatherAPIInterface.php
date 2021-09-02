<?php

namespace App\Services\WeatherServices;

use App\Services\WeatherInfo;

interface WeatherAPIInterface
{

    public function __construct();
    public function GetForecastData($place) : WeatherResponseObject;
    public function GetAPIName() : string;
}
