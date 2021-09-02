<?php

use Database\Seeders\WeatherTypesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateWeatherTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_types', function (Blueprint $table) {
            $table->id();
            $table->string("type")->unique();
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => WeatherTypesSeeder::class
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather_types');
    }
}
