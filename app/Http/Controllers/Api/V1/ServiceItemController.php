<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceItemController extends Controller
{
    public function index(Service $service)
    {
        $items = $service->items()
            ->where('is_active', 1)
            ->select('id', 'name', 'price', 'description')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'service' => [
                'id' => $service->id,
                'name' => $service->name
            ],
            'items' => $items
        ]);
    }
}
