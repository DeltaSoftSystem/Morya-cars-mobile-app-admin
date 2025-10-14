<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_listing_id', 'image_path', 'is_primary', 'sort_order'
    ];

    public function carListing()
    {
        return $this->belongsTo(CarListing::class);
    }
}
