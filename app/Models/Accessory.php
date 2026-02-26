<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Accessory extends Model
{
    use HasFactory;

      protected $table = 'accessories';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'description',
        'compatibility',
        'price',
        'discount_type',
        'discount_value',
        'discounted_price',
        'tax_percentage',
        'sku',
        'stock',
        'image',
        'gallery',
        'status',

        // New fields
        'is_replaceable',
        'is_exchangeable',
        'is_returnable'
    ];

    protected $casts = [
        'gallery' => 'array',
        'price' => 'float',
        'discount_value' => 'float',
        'discounted_price' => 'float',
        'tax_percentage' => 'float'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($accessory) {
            if (empty($accessory->slug)) {
                $accessory->slug = Str::slug($accessory->name);
            }
        });

        static::updating(function ($accessory) {
            if (empty($accessory->slug)) {
                $accessory->slug = Str::slug($accessory->name);
            }
        });
    }

    /* ================= RELATIONS ================= */

    public function category()
    {
        return $this->belongsTo(AccessoryCategory::class, 'category_id');
    }

    /* ================= HELPERS ================= */

    public function getDiscountPercentAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return (int) $this->discount_value;
        }

        if ($this->price > 0) {
            return (int) round((($this->price - $this->discounted_price) / $this->price) * 100);
        }

        return 0;
    }
}
