<?php

namespace App\Services\WeatherServices;

use DateTime;

class WeatherDataObject implements \JsonSerializable
{
    /**
     * @var DateTime
     */
    private $date;
    /**
     * @var string
     */
    private $weather;

    public function __construct(DateTime $date, string $weather) {
        $this->date = $date;
        $this->weather = $weather;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getWeather(): string
    {
        return $this->weather;
    }

    public function jsonSerialize()
    {
        return [
            "date" => $this->date->format("Y-m-d"),
            "weather" => $this->weather,
        ];
    }
}
