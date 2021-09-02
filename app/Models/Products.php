<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['sku', 'name', 'price'];

    protected $hidden = ['id', 'deleted_at', 'created_at', 'updated_at'];

    protected $casts = [
        'price' => 'float'
    ];

    public function weatherTypes()
    {
        return $this->belongsToMany(
            WeatherTypes::class,
            'products_weather',
            'product_id',
            'weather_type');
    }
}
