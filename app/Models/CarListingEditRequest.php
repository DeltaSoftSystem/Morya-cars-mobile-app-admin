<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarListingEditRequest extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'car_listing_id',
        'user_id',
        'changes',
        'status'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function carListing()
    {
        return $this->belongsTo(CarListing::class, 'car_listing_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
