<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_listing_id', 'feature_name', 'is_available'
    ];

    public function carListing()
    {
        return $this->belongsTo(CarListing::class);
    }
}
