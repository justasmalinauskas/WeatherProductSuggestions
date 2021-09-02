<?php

namespace App\Http\Controllers;

use App\Models\Products;
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

    public function getRecommended(Request $request, $city) {
        $api = new MeteoAPIService();
        try {
            $data = $api->GetForecastData($city);
            return response()->json($data, 200, [
                'X-APIProviderLegal' => $api->DataOwner,
            ]);
        } catch (GuzzleException | \Exception $e) {
            $data = [
                "error" => "Failed to fetch data from weather API provider"
            ];
            return response()->json($data, 500, [
                'X-APIProviderLegal' => $api->DataOwner,
            ]);
        }

    }
}
