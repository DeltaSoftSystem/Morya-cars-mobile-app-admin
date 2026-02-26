<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        return response()->json(City::orderBy('name')->get());
    }

    // GET /api/cities/{id}
    public function show($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        return response()->json($city);
    }

    // GET /api/cities-search?q=...
    public function search(Request $request)
    {
        $q = $request->input('q');

        if (!$q) {
            return response()->json(['message' => 'Query is required'], 400);
        }

        $result = City::where('name', 'LIKE', "%$q%")
                        ->orderBy('name')
                        ->get();

        return response()->json($result);
    }

    // OPTIONAL: Paginated list
    public function paginated()
    {
        return response()->json(
            City::orderBy('name')->paginate(50)
        );
    }
}
