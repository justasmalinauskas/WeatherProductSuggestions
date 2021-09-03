<?php

namespace Tests\Unit\classes;

class SimpleWeatherAPI extends \App\Services\WeatherServices\AbstractWeatherAPI
{
    public function testConversion($string) : string {
        return $this->parseWeatherData($string);
    }
}
