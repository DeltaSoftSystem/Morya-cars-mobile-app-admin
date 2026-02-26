<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarMake;

class CarMakeController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->search;

        $makes = CarMake::with('models')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })
            ->orderBy('name')
            ->paginate(20)
            ->appends(['search' => $search]);

        // If AJAX → return only the inner table part (makeTable content)
        if ($request->ajax()) {
            return view('admin.car_makes.table', compact('makes', 'search'))->render();
        }

        // Normal full page
        return view('admin.car_makes.index', compact('makes', 'search'));
    }



    public function create()
    {
        return view('admin.car_makes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:car_makes,name',
            'segment' => 'required|in:standard,premium,luxury',
        ]);

        CarMake::create([
            'name' => $request->name,
            'segment' => $request->segment,
        ]);

        return redirect()->route('car-makes.index')
            ->with('success', 'Car Make created');
    }

    public function edit(CarMake $car_make)
    {
        return view('admin.car_makes.edit', compact('car_make'));
    }

    public function update(Request $request, CarMake $car_make)
    {
        $request->validate([
            'name' => 'required|unique:car_makes,name,' . $car_make->id,
             'segment' => 'required|in:standard,premium,luxury',
        ]);

        $car_make->update($request->only('name','segment'));

        return redirect()->route('car-makes.index')
            ->with('success', 'Car Make updated');
    }

    public function destroy(CarMake $car_make)
    {
        // prevent delete if model exists
        if ($car_make->models()->count() > 0) {
            return back()->with('error', 'Delete not allowed. Make has models.');
        }

        $car_make->delete();

        return redirect()->route('car-makes.index')
            ->with('success', 'Car Make deleted');
    }
}
