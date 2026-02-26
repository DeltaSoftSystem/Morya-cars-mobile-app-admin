<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerProfile extends Model
{
    use HasFactory;
    protected $table = 'dealer_profiles';

    protected $fillable = [
        'user_id',
        'business_name',
        'gst_number',
        'address'
    ];

    public function user()
    {
        return $this->belongsTo(AppUser::class, 'user_id');
    }
}
