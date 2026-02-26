<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Service extends Model
{
    use HasFactory;

     protected $table = 'services';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];

    public function items()
    {
        return $this->hasMany(ServiceItem::class);
    }
    public function requests()
    {
        return $this->hasMany(ServiceRequest::class, 'service_id');
    }
}
