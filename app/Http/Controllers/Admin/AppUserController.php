<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppUser;

class AppUserController extends Controller
{

    /**
     * Display a listing of the app users.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $appUsers = AppUser::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->paginate(4);

        // If AJAX request, return only table HTML
        if ($request->ajax()) {
            return view('app_users.index', compact('appUsers', 'search'))->render();
        }

        return view('app_users.index', compact('appUsers', 'search'));
    }

    /**
     * Display the specified app user details.
     */
    public function show($id)
    {
        $user = AppUser::findOrFail($id);
        return view('app_users.show', compact('user'));
    }

    /**
     * Toggle the status of an app user (active/block).
     */
    public function toggleStatus($id)
    {
        $user = AppUser::findOrFail($id);
        $user->status = $user->status === 'active' ? 'blocked' : 'active';
        $user->save();

        return redirect()->back()->with('success', 'User status updated successfully.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
