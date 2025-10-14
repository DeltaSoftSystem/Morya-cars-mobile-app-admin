<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarListing extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id', 'title', 'make', 'model', 'variant', 'year', 'km_driven',
        'fuel_type', 'transmission', 'body_type', 'color',
        'price', 'expected_price', 'is_negotiable',
        'owner_count', 'registration_state', 'registration_city', 'registration_number',
        'status', 'approved_at', 'admin_rejection_reason',
        'inspection_report_url', 'inspection_summary',
        'is_featured', 'views_count', 'leads_count',
        'has_sunroof', 'has_navigation', 'has_parking_sensor', 'has_reverse_camera',
        'has_airbags', 'has_abs', 'has_esp'
    ];

    // Relations
    public function make()
    {
        return $this->belongsTo(CarMake::class, 'make_id');
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    public function features()
    {
        return $this->hasMany(CarFeature::class);
    }

    public function inspections()
    {
        return $this->hasMany(CarInspection::class);
    }
}
