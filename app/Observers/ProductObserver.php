<?php

namespace App\Observers;

use App\Models\Products;
use App\Rules\ValidSKU;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductObserver
{
    /**
     * Handle the Products "created" event.
     *
     * @param  \App\Models\Products  $products
     * @return void
     */
    public function created(Products $products)
    {
        //
    }

    /**
     * Handle the Products "updated" event.
     *
     * @param  \App\Models\Products  $products
     * @return void
     */
    public function updated(Products $products)
    {
        //
    }

    /**
     * Handle the Products "deleted" event.
     *
     * @param  \App\Models\Products  $products
     * @return void
     */
    public function deleted(Products $products)
    {
        //
    }

    /**
     * Handle the Products "restored" event.
     *
     * @param  \App\Models\Products  $products
     * @return void
     */
    public function restored(Products $products)
    {
        //
    }

    /**
     * Handle the Products "force deleted" event.
     *
     * @param  \App\Models\Products  $products
     * @return void
     */
    public function forceDeleted(Products $products)
    {
        //
    }

    public function saving(Products $product)
    {

        $validator = Validator::make($product->toArray(), [ //inputs are not empty or null
            'sku' => ['required', 'unique:products', new ValidSKU()],
            'name' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

    }
}
