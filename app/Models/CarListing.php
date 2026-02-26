<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarListing extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id', 'title','make_id','model_id', 'make', 'model', 'variant', 'year', 'km_driven',
        'fuel_type', 'transmission', 'body_type', 'color',
        'price', 'expected_price', 'is_negotiable',
        'owner_count', 'registration_state', 'registration_city', 'registration_number',
        'insurance_company', 'insurance_policy_number', 'insurance_upto', 'pucc_number', 'pucc_upto',
        'status','sale_status', 'sold_at', 'approved_at', 'admin_rejection_reason',
        'inspection_report_url', 'inspection_summary',
        'is_featured', 'views_count', 'leads_count',
        'has_sunroof', 'has_navigation', 'has_parking_sensor', 'has_reverse_camera',
        'has_airbags', 'has_abs', 'has_esp',
         'location_city', 'location_state', 'latitude', 'longitude',
         'accident',
         'auction_status'
    ];

    protected $casts = [
        'sold_at' => 'datetime',
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
        return $this->belongsTo(AppUser::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(CarImage::class, 'car_listing_id');
    }

    public function features()
    {
        return $this->hasMany(CarFeature::class, 'car_listing_id');
    }

    public function inspections()
    {
        return $this->hasMany(CarInspection::class);
    }

    public function auction()
    {
        return $this->hasOne(Auction::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'car_listing_id')
                    ->where('booking_status', 'confirmed');
    }

    
    public function pendingEditRequest()
    {
        return $this->hasOne(CarListingEditRequest::class,'car_listing_id')->where('status', 'pending');
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'car_listing_id');
    }

}
