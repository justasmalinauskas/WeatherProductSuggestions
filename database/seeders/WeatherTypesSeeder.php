<?php

namespace Database\Seeders;

use App\Models\WeatherTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeatherTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = config('weather_data.STORED_WEATHER_TYPES');
        for ($i = 0; $i < count($types); $i++) {
            $type = $types[$i];
            $type['id'] = $i + 1;
            WeatherTypes::updateOrCreate($type);
        }
    }
}
