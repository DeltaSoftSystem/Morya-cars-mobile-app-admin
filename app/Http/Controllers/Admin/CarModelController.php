<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarModel;
use App\Models\CarMake;

class CarModelController extends Controller
{
     public function index(Request $request)
{
    $search = $request->search;

    $models = CarModel::with('make')
        ->when($search, function ($q) use ($search) {
            $q->where('car_models.name', 'like', "%$search%")
              ->orWhereHas('make', function ($m) use ($search) {
                  $m->where('name', 'like', "%$search%");
              });
        })
        ->orderBy('car_models.name')
        ->paginate(20)
        ->appends(['search' => $search]);

    // AJAX request → return JSON with table HTML only
    if ($request->ajax()) {
        return response()->json([
            'html' => view('admin.car_models.table', compact('models', 'search'))->render()
        ]);
    }

    return view('admin.car_models.index', compact('models', 'search'));
}


    public function create()
    {
        $makes = CarMake::orderBy('name')->get();
        return view('admin.car_models.create', compact('makes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'make_id' => 'required|exists:car_makes,id',
            'name' => 'required'
        ]);

        CarModel::create($request->only('make_id', 'name'));

        return redirect()->route('car-models.index')
            ->with('success', 'Car Model created');
    }

    public function edit(CarModel $car_model)
    {
        $makes = CarMake::orderBy('name')->get();
        return view('admin.car_models.edit', compact('car_model', 'makes'));
    }

    public function update(Request $request, CarModel $car_model)
    {
        $request->validate([
            'make_id' => 'required|exists:car_makes,id',
            'name' => 'required'
        ]);

        $car_model->update($request->only('make_id', 'name'));

        return redirect()->route('car-models.index')
            ->with('success', 'Car Model updated');
    }

    public function destroy(CarModel $car_model)
    {
        $car_model->delete();

        return redirect()->route('car-models.index')
            ->with('success', 'Car Model deleted');
    }
}
