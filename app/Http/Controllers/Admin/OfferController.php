<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('applies_to', 'like', '%' . $request->search . '%');
        }

        $offers = $query->orderBy('created_at', 'desc')
                        ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.offers.table', compact('offers'))->render()
            ]);
        }

        return view('admin.offers.index', compact('offers'));
    }


    /** Show create form */
    public function create()
    {
        return view('admin.offers.create');
    }

    /** Store offer */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'applies_to' => 'required|in:accessories,workshop,car_listing',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Offer::create($request->all());

        return redirect()->route('offers.index')
            ->with('success', 'Offer created successfully');
    }

    /** Edit form */
    public function edit(Offer $offer)
    {
        return view('admin.offers.edit', compact('offer'));
    }

    /** Update offer */
    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'applies_to' => 'required|in:accessories,workshop,car_listing',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $offer->update($request->all());

        return redirect()->route('offers.index')
            ->with('success', 'Offer updated successfully');
    }

    /** Enable / Disable */
    public function toggleStatus(Offer $offer)
    {
        $offer->update([
            'is_active' => !$offer->is_active
        ]);

        return back()->with('success', 'Offer status updated');
    }

    /** Delete */
    public function destroy(Offer $offer)
    {
        $offer->delete();
        return back()->with('success', 'Offer deleted');
    }
}
