<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accessory;
use App\Models\AccessoryCategory;
use App\Models\AccessoryBooking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AccessoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Accessory::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhereHas('category', function ($c) use ($search) {
                    $c->where('name', 'like', "%{$search}%");
                });
            });
        }

        $accessories = $query->latest()->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.accessories.table', compact('accessories'))->render()
            ]);
        }

        return view('admin.accessories.index', compact('accessories'));
    }

    public function create()
    {
        $categories = AccessoryCategory::where('status', 1)->get();
        return view('admin.accessories.create', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required',
        'name'        => 'required|string|max:255',
        'brand'       => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'price'       => 'required|numeric',
        'discounted_price' => 'required|numeric',
        'stock'       => 'required|integer',
        'image'       => 'nullable|image'
    ]);

    $data = $request->except(['gallery']);

    $data['slug'] = Str::slug($request->name);

    // ✅ Checkbox Handling
    $data['is_replaceable'] = $request->has('is_replaceable') ? 1 : 0;
    $data['is_exchangeable'] = $request->has('is_exchangeable') ? 1 : 0;
    $data['is_returnable']   = $request->has('is_returnable') ? 1 : 0;

    /* Main Image */
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')
            ->store('accessories', 'public');
    }

    /* Gallery Images */
    if ($request->hasFile('gallery')) {
        $gallery = [];
        foreach ($request->file('gallery') as $file) {
            $gallery[] = $file->store('accessories/gallery', 'public');
        }
        $data['gallery'] = json_encode($gallery);
    }

    Accessory::create($data);

    return redirect()->route('accessories.index')
        ->with('success', 'Accessory added successfully');
}

    public function edit($id)
    {
        $accessory = Accessory::findOrFail($id);
        $categories = AccessoryCategory::where('status', 1)->get();

        return view('admin.accessories.edit', compact('accessory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required',
            'name'        => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'discounted_price' => 'required|numeric',
            'stock' => 'required|integer',
            'is_replaceable' => 'nullable|boolean',
            'is_exchangeable' => 'nullable|boolean',
            'is_returnable' => 'nullable|boolean',
            'image'       => 'nullable|image'
        ]);

        $accessory = Accessory::findOrFail($id);
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        /* Replace Main Image */
        if ($request->hasFile('image')) {
            if ($accessory->image) {
                Storage::disk('public')->delete($accessory->image);
            }
            $data['image'] = $request->file('image')
                ->store('accessories', 'public');
        }

        /* Replace Gallery */
        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('accessories/gallery', 'public');
            }
            $data['gallery'] = json_encode($gallery);
        }

        $accessory->update($data);

        return redirect()->route('accessories.index')
            ->with('success', 'Accessory updated successfully');
    }

    public function show($id)
    {
        $accessory=Accessory::find($id);
        return view('admin.accessories.show', compact('accessory'));
    }

    public function destroy($id)
    {
        $accessory = Accessory::findOrFail($id);

        if ($accessory->image) {
            Storage::disk('public')->delete($accessory->image);
        }

        $accessory->delete();

        return redirect()->back()
            ->with('success', 'Accessory deleted');
    }

    public function bookings(Request $request)
    {
        $query = AccessoryBooking::with('accessory');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.accessories.bookings', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,closed'
        ]);

        AccessoryBooking::where('id', $id)
            ->update(['status' => $request->status]);

        return back()->with('success', 'Booking status updated');
    }
}
