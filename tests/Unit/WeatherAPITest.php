<?php

use Tests\Unit\classes\SimpleWeatherAPI;

class WeatherAPITest extends \Tests\TestCase
{
    private $weatherAPITest;


    public function setUp(): void
    {
        parent::setUp();
        $this->weatherAPITest = new SimpleWeatherAPI();
    }

    public function testConversion()
    {
        $this->assertEquals("sunny", $this->weatherAPITest->testConversion("clear"));
        $this->assertEquals("n/a", $this->weatherAPITest->testConversion("notExisting"));
        $this->assertEquals("rain", $this->weatherAPITest->testConversion("light-rain"));
    }
}
