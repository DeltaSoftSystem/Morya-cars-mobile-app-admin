<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessoryCategory;
use Illuminate\Support\Str;

class AccessoryCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = AccessoryCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $categories = $query->latest()->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'admin.accessories.categories.table',
                    compact('categories')
                )->render()
            ]);
        }

        return view('admin.accessories.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.accessories.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
        ]);

        AccessoryCategory::create([
            'name'   => $request->name,
            'slug'   => Str::slug($request->name),
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('accessory-categories.index')
            ->with('success', 'Category added successfully');
    }

    public function edit($id)
    {
        $category = AccessoryCategory::findOrFail($id);
        return view('admin.accessories.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
        ]);

        $category = AccessoryCategory::findOrFail($id);
        $category->update([
            'name'   => $request->name,
            'slug'   => Str::slug($request->name),
            'status' => $request->status
        ]);

        return redirect()->route('accessory-categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        AccessoryCategory::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Category deleted');
    }
}
