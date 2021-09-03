<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\WeatherTypes;
use App\Services\WeatherInfo;
use App\Services\WeatherServices\MeteoAPIService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        return Products::simplePaginate(100);
    }

    public function show($sku)
    {
        try {
            return Products::where('sku', '=', $sku)->with('weatherTypes')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Product does not exist"], 404);
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
            return response()->json(['error' => "Product does not exist"], 404);
        }

    }

    public function delete(Request $request, $sku)
    {
        try {
            $product = Products::where('sku', '=', $sku)->firstOrFail();
            $product->delete();
            return response()->json($product, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Product does not exist"], 404);
        }

    }

    public function getRecommended(Request $request, $city)
    {
        try {
            $validator = Validator::make(
                ["city" => $city]
                ,
                [
                'city' => ['required'],
                ]
            );
            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }
            $api = new MeteoAPIService();
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
            $status = 500;
            $data = [
                "error" => "Failed to fetch data from weather API provider"
            ];
            Log::error($e->getMessage(), $e->getTrace());
            if ($e instanceof GuzzleException) {
                $status = $e->getCode();
            }
            if($status === 404) {
                $data = [
                    "error" => "Not Found data for place: ". $city
                ];
            }
            return response()->json($data, $status, [
                'X-APIProviderLegal' => $api->DataOwner,
            ]);
        }

    }
}
