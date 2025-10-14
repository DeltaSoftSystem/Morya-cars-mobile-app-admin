<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = ['make_id', 'name'];
    public function make()
    {
        return $this->belongsTo(CarMake::class, 'make_id');
    }
}
