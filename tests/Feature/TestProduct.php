<?php

use App\Models\Products;

class TestProduct extends \Tests\TestCase
{
    public function test_making_an_api_request()
    {
        $sku = "TES-".rand(1, 999);
        $response = $this->postJson('/api/products', ['name' => 'Sally', 'sku' => $sku, 'price' => 0.99 ]);

        $response
            ->assertStatus(201)
            ->assertJson(Products::where('sku', '=', $sku)->firstOrFail()->toArray());
    }
}
