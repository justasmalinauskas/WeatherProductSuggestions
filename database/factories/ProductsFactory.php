<?php

namespace Database\Factories;

use App\Models\Products;
use App\Models\WeatherTypes;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Products::class;


    private function productName($nbWords = 5)
    {
        $sentence = $this->faker->sentence($nbWords);
        return substr($sentence, 0, strlen($sentence) - 1);
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sku' => $this->faker->unique()->regexify('[A-Z]{2,3}\-\d{1,4}'),
            'name' => $this->productName(),
            'price' => $this->faker->randomFloat(2, 0.99, 999.99),
        ];
    }
}
