<?php

namespace App\Services\WeatherServices;

use App\Services\WeatherInfo;
use DateInterval;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MeteoAPIService extends AbstractWeatherAPI implements WeatherAPIInterface
{

    private $client;


    public function __construct()
    {
        parent::__construct();
        $this->DataOwner = "Lietuvos hidrometeorologijos tarnyba prie Aplinkos ministerijos(LHMT)";
        $this->apiURL = "https://api.meteo.lt/v1/";
        $this->client = new Client([
            'base_uri' => $this->apiURL,
            'timeout'  => 30.0,
            'verify' => base_path('cacert.pem'),
        ]);

    }

    private function transformPlaceToCode($place): string
    {
        setlocale(LC_ALL, 'en_US.UTF-8');
        $string = trim($place);
        $stage1= preg_replace("/[^[:alnum:][:space:]]/u", '', $string);
        $stage2 = iconv('UTF-8', 'ASCII//TRANSLIT', $stage1);
        $stage3 = strtolower(preg_replace("/\s+/", '-', $stage2));
        return $stage3;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function GetForecastData($place): WeatherResponseObject
    {
        $format = 'Y-m-d H:i:s';
        $placeCode = $this->transformPlaceToCode($place);
        $response = $this->client->get("places/".$placeCode."/forecasts/long-term");
        $parsed = json_decode($response->getBody());

        $dateIn3Days = new \DateTime("now", new \DateTimeZone("UTC"));
        $dateIn3Days->setTime(0, 0);
        $dateIn3Days->add(new DateInterval('P3D'));

        $conversionTable = config('weather_data.WEATHER_TYPE') ?? [];
        $storedForecasts = [];
        foreach ($parsed->forecastTimestamps as $forecastTimestamp) {
            $date = \DateTime::createFromFormat($format, $forecastTimestamp->forecastTimeUtc);
            if($date < $dateIn3Days) {
                $dateYmd = $date->format("Y-m-d");
                if(!isset($storedForecasts[$dateYmd])) {
                    $startDate = clone $date;
                    $startDate->setTime(0, 0);
                    $storedForecasts[$dateYmd] = [
                        "date" => $startDate,
                        "weatherData" => []
                    ];
                }
                $storedForecasts[$dateYmd]["weatherData"][] =
                    $conversionTable[strtolower(trim($forecastTimestamp->conditionCode))];
            }
        }
        $returnForecasts = [];
        foreach ($storedForecasts as $storedForecast) {
            $counts = array_count_values($storedForecast['weatherData']);
            arsort($counts);
            $mode = key($counts);
            $returnForecasts[] = new WeatherDataObject($storedForecast['date'], $mode);
        }
        return new WeatherResponseObject($returnForecasts, $parsed->place->name);
    }

    public function GetAPIName(): string
    {
        return "meteo";
    }
}
