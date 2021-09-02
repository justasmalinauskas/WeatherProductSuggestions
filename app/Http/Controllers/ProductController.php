<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\WeatherTypes;
use App\Services\WeatherInfo;
use App\Services\WeatherServices\MeteoAPIService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Products::simplePaginate(100);
    }

    public function show($sku)
    {
        // dd(Products::find($id)::with('weatherTypes')->first());
        //return Products::where('sku', '=', $sku)->with('weatherTypes')->firstOrFail();
        try {
            return Products::where('sku', '=', $sku)->with('weatherTypes')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return ['error' => "Product does not exist"];
        }
    }

    public function store(Request $request)
    {
        return Products::create($request->all());
    }

    public function update(Request $request, $sku)
    {

        try {
            $product = Products::where('sku', '=', $sku)->firstOrFail();
            $product->update($request->all());
            return $product;
        } catch (ModelNotFoundException $e) {
            return ['error' => "Product does not exist"];
        }

    }

    public function delete(Request $request, $sku)
    {
        try {
            $product = Products::where('sku', '=', $sku)->firstOrFail();
            $product->delete();
            return 204;
        } catch (ModelNotFoundException $e) {
            return ['error' => "Product does not exist"];
        }

    }

    public function getRecommended(Request $request, $city)
    {
        $api = new MeteoAPIService();
        try {
            $data = WeatherInfo::FetchWeatherData($city, $api);
            $response = [];
            $response['city'] = $data->getPlace();
            $response['recomendations'] = [];
            foreach ($data->getWeatherData() as $weatherData) {
                $weather = $weatherData->getWeather();
                $weatherType = WeatherTypes::where('type', '=', $weather)->firstOrFail();
                $relatedProducts = Products::whereHas('weatherTypes', function ($query) use ($weatherType) {
                    return $query->where('weather_type', '=', $weatherType->id);
                })->inRandomOrder()->take(2)->get()->toArray();
                $response['recomendations'][] = [
                    "weather_forecast" => $weatherType->type,
                    "date" => $weatherData->getDate()->format("Y-m-d"),
                    "products" => $relatedProducts
                ];
            }
            return response()->json($response, 200, [
                'X-APIProviderLegal' => $api->DataOwner,
            ]);
        } catch (\Exception $e) {
            $data = [
                "error" => "Failed to fetch data from weather API provider"
            ];
            return response()->json($data, 500, [
                'X-APIProviderLegal' => $api->DataOwner,
            ]);
        }

    }
}
