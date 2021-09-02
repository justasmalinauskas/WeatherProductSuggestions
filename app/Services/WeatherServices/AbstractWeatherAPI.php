<?php

namespace App\Services\WeatherServices;

abstract class AbstractWeatherAPI
{
    protected $apiURL;
    public $DataOwner;

    public function __construct()
    {
    }
}
