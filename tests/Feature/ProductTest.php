<?php

use App\Models\Products;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{


    private function createProduct($sku) {
        $response = $this->postJson('/api/products', ['name' => 'Sally', 'sku' => $sku, 'price' => 0.99]);
        return  $response;
    }

    private function deleteProduct($sku) {
        $response = $this->delete('/api/products/' . $sku);
        return  $response;
    }

    private function forceDelete($sku) {
        $product = Products::withTrashed()->where('sku', '=', $sku)->first();
        $product->forceDelete();
    }

    public function test_making_an_api_POST_request()
    {
        $validSKU = "UNI-001";
        $this->forceDelete($validSKU);
        $this->createProduct($validSKU)
            ->assertStatus(201)
            ->assertJson(Products::where('sku', '=', $validSKU)->firstOrFail()->toArray());

    }


    public function test_making_an_api_GET_request()
    {
        $validSKU = "UNI-002";
        $this->forceDelete($validSKU);
        $this->createProduct($validSKU);
        $response = $this->get('/api/products/' . $validSKU);

        $response
            ->assertStatus(200)
            ->assertJson(Products::where('sku', '=', $validSKU)->firstOrFail()->toArray());
    }

    public function test_making_an_api_PUT_request()
    {
        $validSKU = "UNI-003";
        $this->forceDelete($validSKU);
        $this->createProduct($validSKU);
        $response = $this->putJson('/api/products/' . $validSKU, ['name' => 'Sally Put', 'sku' => $validSKU, 'price' => 0.99]);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->where('name', "Sally Put")
                    ->etc();
            }
            );
    }

    public function test_making_an_api_DELETE_request()
    {
        $validSKU = "UNI-004";
        $this->forceDelete($validSKU);
        $this->createProduct($validSKU);
        $this->deleteProduct($validSKU)->assertStatus(204);

    }

    public function test_making_an_api_request_to_fail()
    {
        $sku = "TESTAS-" . rand(1, 999);
        $response = $this->postJson('/api/products', ['name' => 'Sally', 'sku' => $sku, 'price' => 0.99]);

        $response
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                return $json->whereType('errors.sku', 'array')->etc();
            }
            );
    }

    public function test_get_recommended()
    {
        $response = $this->get('/api/products/recommended/kaunas');
        $response
            ->assertStatus(200)->assertJson(function (AssertableJson $json) {
                return $json->where('city', "Kaunas")
                    ->whereAllType([
                        'recomendations' => 'array',
                        'recomendations.0.products.0.sku' => 'string',
                        'recomendations.0.products.0.price' => 'double',
                    ])
                    ->etc();
            }
            );
    }

    public function test_not_found_recommended()
    {
        $response = $this->get('/api/products/recommended/ASNEEGZISTUOJU');
        $response
            ->assertStatus(404)->assertJson(function (AssertableJson $json) {
                return $json->whereType('error', 'string')->etc();
            }
            );
    }
}
