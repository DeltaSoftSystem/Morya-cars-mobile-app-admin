<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarListing;
use App\Models\CarModel;
use App\Models\CarMake;

class SellCarController extends Controller
{
    public function index()
    {
        $makes = CarMake::all();
        return view('web.sell.index',compact('makes'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'phone'     => 'required',
            'make_id'   => 'required|exists:car_makes,id',
            'model_id'  => 'required|exists:car_models,id',
            'year'      => 'required|integer',
            'price'     => 'required|numeric',
            'images.*'  => 'image|max:2048'
        ]);

        $listing = CarListing::create([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'make_id'   => $request->make_id,
            'model_id'  => $request->model_id,
            'year'      => $request->year,
            'price'     => $request->price,
            'status'    => 0
        ]);

        if($request->hasFile('images')){
            foreach($request->file('images') as $image){
                $path = $image->store('sell_cars','public');

                $listing->images()->create([
                    'image'=>$path
                ]);
            }
        }

        return response()->json(['status'=>true]);
    }

    public function getModels($id)
    {
        return CarModel::where('make_id',$id)->get();
    }
}
