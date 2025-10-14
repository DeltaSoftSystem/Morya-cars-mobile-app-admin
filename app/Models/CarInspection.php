<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarInspection extends Model
{
    use HasFactory;

     protected $fillable = [
        'car_listing_id', 'report_url', 'inspection_date',
        'inspector_name', 'inspection_center', 'summary', 'status'
    ];

    protected $casts = [
        'summary' => 'array', // cast JSON summary to array
        'inspection_date' => 'date',
    ];

    public function carListing()
    {
        return $this->belongsTo(CarListing::class);
    }
}
