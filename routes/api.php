<?php

use App\Http\Middleware\CacheForgetMiddleware;
use App\Http\Middleware\CacheSaveMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {

    Route::get('products', 'App\Http\Controllers\ProductController@index');
    Route::post('products', 'App\Http\Controllers\ProductController@store');

    Route::middleware(CacheForgetMiddleware::class)->group(function () {
        Route::put('products/{sku}', 'App\Http\Controllers\ProductController@update');
        Route::delete('products/{sku}', 'App\Http\Controllers\ProductController@delete');
    });

    Route::middleware(CacheSaveMiddleware::class)->group(function () {
        Route::get('products/{sku}', 'App\Http\Controllers\ProductController@show');
        Route::get('products/recommended/{city}', 'App\Http\Controllers\ProductController@getRecommended');
    });

});
