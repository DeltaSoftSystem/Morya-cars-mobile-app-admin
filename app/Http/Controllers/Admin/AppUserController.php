<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppUser;
use App\Services\NotificationService;

class AppUserController extends Controller
{

    /**
     * Display a listing of the app users.
     */
    public function index(Request $request)
{
    $search = $request->query('search');

    $appUsers = AppUser::whereIn('role', ['buyer', 'seller']) // 👈 EXCLUDE DEALERS
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    // AJAX request → return same view (your current setup)
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

        // 📧 Send status change email
        if ($user->status === 'blocked') {

            NotificationService::sendEmail(
                $user->email,
                'Your Account Has Been Blocked',
                [
                    'name'    => $user->name,
                    'message' => 'Your Morya Auto Hub account has been temporarily blocked.',
                    'extra'   => 'If you believe this is a mistake, please contact support.'
                ]
            );

        } else {

            NotificationService::sendEmail(
                $user->email,
                'Your Account Has Been Activated',
                [
                    'name'    => $user->name,
                    'message' => 'Good news! Your Morya Auto Hub account has been reactivated.',
                    'extra'   => 'You can now log in and continue using our services.'
                ]
            );
        }
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
