<?php

namespace App\Services\WeatherServices;

class WeatherResponseObject implements \JsonSerializable
{
    /**
     * @var WeatherDataObject[]
     */
    private $weatherData;
    private $place;

    public function __construct(array $weatherData, string $place)
    {
        $this->weatherData = $weatherData;
        $this->place = $place;
    }

    public function jsonSerialize(): array
    {
        return [
            "place" => $this->place,
            "weatherData" => $this->weatherData,
        ];
    }

    /**
     * @return WeatherDataObject[]
     */
    public function getWeatherData(): array
    {
        return $this->weatherData;
    }

    /**
     * @return string
     */
    public function getPlace(): string
    {
        return $this->place;
    }
}
