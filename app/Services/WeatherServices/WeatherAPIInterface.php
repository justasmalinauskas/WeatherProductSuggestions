<?php

namespace App\Services\WeatherServices;

interface WeatherAPIInterface
{

    public function __construct();
    public function GetForecastData($place);
    public function GetAPIName() : string;
}
