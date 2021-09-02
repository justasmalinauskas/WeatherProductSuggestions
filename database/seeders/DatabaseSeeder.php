<?php

namespace Database\Seeders;

use App\Models\WeatherTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(WeatherTypesSeeder::class);
        if (app()->environment() !== 'production') {
            DB::statement("SET foreign_key_checks=0");
            DB::table('products')->truncate();
            DB::statement("SET foreign_key_checks=1");
            $weatherTypes = WeatherTypes::all();
            \App\Models\Products::factory(10000)->create()->each(function($product) use ($weatherTypes) {
                $product->weatherTypes()->attach(
                    $weatherTypes->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
        }
    }
}
