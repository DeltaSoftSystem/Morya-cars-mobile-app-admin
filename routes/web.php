<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AppUserController;
use App\Http\Controllers\Admin\CarListingController;
use App\Http\Controllers\Admin\SubscriptionPlanController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('app-users', [AppUserController::class, 'index'])->name('app-users.index');
    Route::get('app-users/{id}', [AppUserController::class, 'show'])->name('app-users.show');
    Route::post('app-users/{id}/toggle-status', [AppUserController::class, 'toggleStatus'])->name('app-users.toggleStatus');

    // CarListing
    Route::get('car-listings', [CarListingController::class, 'index'])->name('admin.car-listings.index');
    Route::get('car-listings/{car}', [CarListingController::class, 'show'])->name('admin.car-listings.show');
    Route::post('car-listings/{car}/approve', [CarListingController::class, 'approve'])->name('admin.car-listings.approve');
    Route::post('car-listings/{car}/reject', [CarListingController::class, 'reject'])->name('admin.car-listings.reject');

    //Dependent Models Dropdown
    Route::get('/admin/get-models/{make}', [CarListingController::class, 'getModels'])->name('admin.get-models');

    //Subscription Plan
    Route::resource('subscriptions/plans', SubscriptionPlanController::class)->names([
        'index' => 'admin.subscriptions.plans.index',
        'create' => 'admin.subscriptions.plans.create',
        'store' => 'admin.subscriptions.plans.store',
        'edit' => 'admin.subscriptions.plans.edit',
        'update' => 'admin.subscriptions.plans.update',
        'destroy' => 'admin.subscriptions.plans.destroy',
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



