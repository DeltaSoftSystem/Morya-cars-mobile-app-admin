<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

     protected $table = 'offers';

    protected $fillable = [
        'title',
        'description',
        'discount_type',
        'discount_value',
        'applies_to',
        'start_date',
        'end_date',
        'is_active'
    ];
}
