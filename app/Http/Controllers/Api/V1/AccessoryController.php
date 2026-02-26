<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accessory;
use App\Models\AccessoryCategory;

class AccessoryController extends Controller
{
     /* =======================
       CATEGORY LIST
    ======================= */
    public function categories()
    { 
        $categories = AccessoryCategory::where('status', 1)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }

    /* =======================
       ACCESSORIES LIST
    ======================= */
    public function index(Request $request)
{
    $query = Accessory::where('status', 1)
        ->with('category:id,name,slug');

    /* 🔍 Search */
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%");
        });
    }

    /* 🗂 Filter by Category ID */
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    /* 🗂 Filter by Category Slug (NEW – Recommended) */
    if ($request->filled('category_slug')) {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('slug', $request->category_slug);
        });
    }

    /* 💰 Price Filters */
    if ($request->filled('min_price')) {
        $query->where('discounted_price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('discounted_price', '<=', $request->max_price);
    }

    /* 📦 In Stock Only */
    if ($request->boolean('in_stock')) {
        $query->where('stock', '>', 0);
    }

    /* 🔽 Sorting */
    if ($request->filled('sort')) {
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('discounted_price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('discounted_price', 'desc');
                break;

            case 'newest':
                $query->latest();
                break;

            default:
                $query->latest();
        }
    } else {
        $query->latest();
    }

    $accessories = $query->paginate(10);

    return response()->json([
        'status' => true,
        'data' => $accessories->through(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'brand' => $item->brand,
                'description'=>$item->description,

                'category' => [
                    'id' => $item->category->id ?? null,
                    'name' => $item->category->name ?? null,
                    'slug' => $item->category->slug ?? null,
                ],

                'price' => $item->price,
                'discounted_price' => $item->discounted_price,
                'discount_percent' => $item->discount_percent,

                'image' => $item->image
                    ? asset('storage/' . $item->image)
                    : null,

                'stock' => $item->stock,
                'is_available' => $item->stock > 0,

                // ✅ Added
                'is_replaceable' => (bool) $item->is_replaceable,
                'is_exchangeable' => (bool) $item->is_exchangeable,
                'is_returnable' => (bool) $item->is_returnable,
            ];
        }),
        'pagination' => [
            'current_page' => $accessories->currentPage(),
            'last_page' => $accessories->lastPage(),
            'total' => $accessories->total()
        ]
    ]);
}
    /* =======================
       ACCESSORY DETAIL
    ======================= */
    public function show($id)
{
    $accessory = Accessory::with('category:id,name')
        ->where('status', 1)
        ->findOrFail($id);

    return response()->json([
        'status' => true,
        'data' => [
            'id' => $accessory->id,
            'name' => $accessory->name,
            'brand' => $accessory->brand,
            'category' => $accessory->category->name ?? null,

            'description' => $accessory->description,
            'compatibility' => $accessory->compatibility,

            'price' => $accessory->price,
            'discounted_price' => $accessory->discounted_price,
            'discount_percent' => $accessory->discount_percent,

            'image' => $accessory->image
                ? asset('storage/' . $accessory->image)
                : null,

            'gallery' => collect($accessory->gallery)->map(function ($img) {
                return asset('storage/' . $img);
            }),

            'stock' => $accessory->stock,
            'is_available' => $accessory->stock > 0,

            // ✅ Added Policy Fields
            'is_replaceable' => (bool) $accessory->is_replaceable,
            'is_exchangeable' => (bool) $accessory->is_exchangeable,
            'is_returnable' => (bool) $accessory->is_returnable,
        ]
    ]);
}
}
