<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsWeather extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_weather', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id', false, true);
            $table->bigInteger('weather_type', false, true);


            $table->foreign('product_id')
                ->references('id')
                ->on('products')->onDelete('cascade');

            $table->foreign('weather_type')
                ->references('id')
                ->on('weather_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_weather');
    }
}
