<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceItem;

class ServiceItemController extends Controller
{
    public function index(Service $service)
    {
        $items = $service->items()->latest()->get();
        return view('admin.service_items.index', compact('service','items'));
    }

    public function create(Service $service)
    {
        return view('admin.service_items.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'price' => 'nullable|numeric',
            'is_active' => 'required|boolean'
        ]);

        $service->items()->create(
            $request->only('name','description','price','is_active')
        );

        return redirect()
            ->route('services.items.index', $service->id)
            ->with('success','Item added');
    }

    public function edit(Service $service, ServiceItem $item)
    {
        return view('admin.service_items.edit', compact('service','item'));
    }

    public function update(Request $request, Service $service, ServiceItem $item)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'price' => 'nullable|numeric',
            'is_active' => 'required|boolean'
        ]);

        $item->update(
            $request->only('name','description','price','is_active')
        );

        return redirect()
            ->route('services.items.index', $service->id)
            ->with('success','Item updated');
    }

    public function destroy(Service $service, ServiceItem $item)
    {
        $item->delete();
        return back()->with('success','Item deleted');
    }
}
