<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherTypes extends Model
{
    use HasFactory;

    protected $hidden = ['id', 'pivot', 'created_at', 'updated_at'];
}
