<?php

namespace App\Services;

use App\Services\WeatherServices\WeatherAPIInterface;
use App\Services\WeatherServices\WeatherResponseObject;
use Illuminate\Support\Facades\Cache;

class WeatherInfo
{
    /**
     * @param $place
     * @param WeatherAPIInterface $apiToUse
     * @return WeatherResponseObject
     */
    public static function FetchWeatherData($place, WeatherAPIInterface $apiToUse): WeatherResponseObject
    {
        $storedKey = "WeatherData.".$apiToUse->GetAPIName()."".strtolower(trim($place));
        if (Cache::has($storedKey)) {
            return Cache::get($storedKey);
        } else {
            $data = $apiToUse->GetForecastData($place);
            /*
                As weather data is time sensitive for the next day, we're setting caching time to be next day aware,
                so if midnight happens in two minutes, we cache only for those two minutes.
            */
            Cache::add($storedKey, $data, self::getCacheTime());
            return $data;
        }
    }

    private static function getCacheTime(int $secondsToCacheMax = 300) {
        $now = new \DateTime('now', new \DateTimeZone("UTC"));
        $startOfNextDay = clone $now;
        $startOfNextDay->add(new \DateInterval('P1D'));
        $startOfNextDay->setTime(0,0);
        $diff = $startOfNextDay->getTimestamp() - $now->getTimestamp();
        return min($secondsToCacheMax, $diff);
    }
}
