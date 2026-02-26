<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CarListing;

class HomeController extends Controller
{
    /**
     * Public Home Page
     */
    public function index()
    {
         $cars = CarListing::with('images')
            ->whereNull('deleted_at')
            ->whereIn('status', ['approved', 'active']) // adjust if needed
            ->orderByDesc('id')
            ->limit(6)
            ->get();
        
       // dd($cars);

        return view('web.home', compact('cars'));
    }
}
