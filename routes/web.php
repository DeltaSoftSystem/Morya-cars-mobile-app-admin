<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AppUserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CarListingController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\CarMakeController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\AuctionController;
use App\Http\Controllers\Admin\AuctionDepositController;
use App\Http\Controllers\Admin\SellerPaymentController;
use App\Http\Controllers\Admin\DealerController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\AccessoryController;
use App\Http\Controllers\Admin\AccessoryCategoryController;
use App\Http\Controllers\Admin\CarSyncController;
use App\Http\Controllers\Admin\ServiceItemController;
use App\Http\Controllers\Admin\OfferController;
use Illuminate\Support\Facades\Artisan;




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

    //Dealer
    Route::get('dealers', [DealerController::class, 'index'])->name('admin.dealers.index');
    Route::get('dealers/kyc', [DealerController::class, 'kyc'])->name('admin.dealers.kyc');
    Route::post('dealers/kyc/{id}/approve', [DealerController::class, 'approveKyc'])->name('admin.dealers.kyc.approve');
    Route::post('dealers/kyc/{id}/reject', [DealerController::class, 'rejectKyc'])->name('admin.dealers.kyc.reject');

    // CarListing
    Route::get('car-listings', [CarListingController::class, 'index'])->name('admin.car-listings.index');
    Route::get('car-listings/{car}', [CarListingController::class, 'show'])->name('admin.car-listings.show');
    Route::post('car-listings/{car}/approve', [CarListingController::class, 'approve'])->name('admin.car-listings.approve');
    Route::post('car-listings/{car}/reject', [CarListingController::class, 'reject'])->name('admin.car-listings.reject');

    Route::post('car-listings/edit-requests/{id}/approve',[CarListingController::class, 'approveEditRequest'])->name('admin.car-listings.edit-approve');
    Route::post('car-listings/edit-requests/{id}/reject',[CarListingController::class, 'rejectEditRequest'])->name('admin.car-listings.edit-reject');

    Route::get('car-listing/sold', [CarListingController::class, 'sold'])->name('admin.car-listings.sold');


    //Dependent Models Dropdown
    Route::get('/admin/get-models/{make}', [CarListingController::class, 'getModels'])->name('admin.get-models');

    //Subscription Plan
    Route::resource('subscriptions/plans', SubscriptionPlanController::class)->names([
        'index' => 'admin.subscriptions_plans.index',
        'create' => 'admin.subscriptions_plans.create',
        'store' => 'admin.subscriptions_plans.store',
        'edit' => 'admin.subscriptions_plans.edit',
        'update' => 'admin.subscriptions_plans.update',
        'destroy' => 'admin.subscriptions_plans.destroy',
    ]);
    Route::resource('car-makes', CarMakeController::class)->except(['show']);
    Route::resource('car-models', CarModelController::class)->except(['show']);

    Route::get('/get-models/{make_id}', function ($make_id) {
        return \App\Models\CarModel::where('make_id', $make_id)->get();
    });

    Route::resource('bookings', BookingController::class);
    // Payment Proof Routes
     // Upload proof
    Route::post('bookings/{id}/upload-proof',[BookingController::class, 'uploadProof'])->name('bookings.upload-proof');
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Verify / Reject
    Route::post('payment-proof/{proof}/verify', [BookingController::class, 'verifyPaymentProof'])->name('payment-proof.verify');
    Route::post('payment-proof/{proof}/reject',[BookingController::class, 'rejectPaymentProof'])->name('payment-proof.reject');
    Route::delete('payment-proof/{proof}',[BookingController::class, 'deleteProof'])->name('payment-proof.delete');

    Route::post('bookings/{booking}/seller-payment',[SellerPaymentController::class, 'store'])->name('seller-payment.store');

    //Auction
    Route::get('auctions/requested', [AuctionController::class, 'requested'])->name('auctions.requested');
    Route::get('auctions/scheduled', [AuctionController::class, 'scheduled'])->name('auctions.scheduled');
    Route::get('auctions/history',   [AuctionController::class, 'history'])->name('auctions.history');

    Route::post('auctions/{carListing}/approve', [AuctionController::class, 'approve'])->name('auctions.approve');
    Route::post('auctions/{carListing}/reject', [AuctionController::class, 'reject'])->name('auctions.reject');
    
    // Show schedule form
    Route::get('/auctions/{auction}/schedule', [AuctionController::class, 'showScheduleForm'])->name('auctions.schedule.form');

    // Handle form submission
    Route::post('/auctions/{auction}/schedule', [AuctionController::class, 'schedule'])->name('auctions.schedule.submit');
    Route::get('/auctions/{id}', [AuctionController::class, 'show'])->name('auctions.show');

    Route::get('auction-deposits', [AuctionDepositController::class, 'index'])->name('deposits.index');

    // Approve deposit
    Route::post('auction-deposits/{id}/approve', [AuctionDepositController::class, 'approve'])->name('deposits.approve');

    // Reject deposit
    Route::post('auction-deposits/{id}/reject', [AuctionDepositController::class, 'reject'])->name('deposits.reject');

    // Refund deposit
    Route::post('auction-deposits/{id}/refund', [AuctionDepositController::class, 'refund'])->name('deposits.refund');

    //Service Request
    Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::get('/service-requests/{id}', [ServiceRequestController::class, 'show'])->name('service-requests.show');
    Route::post('/service-requests/{id}/update-status', [ServiceRequestController::class, 'updateStatus'])->name('service-requests.update-status');

    //Service master
    Route::resource('services', ServiceController::class);
    Route::resource('services.items',ServiceItemController::class);


    //Accessories 
    Route::resource('accessory-categories', AccessoryCategoryController::class);
    Route::resource('accessories', AccessoryController::class);
    Route::get('accessory-bookings',[AccessoryController::class, 'bookings'])->name('accessories.bookings');
    Route::post('accessory-bookings/{id}/status',[AccessoryController::class, 'updateBookingStatus'])->name('accessories.bookings.status');

    //Offers
    Route::resource('offers', OfferController::class);
    Route::post('offers/{offer}/toggle', [OfferController::class, 'toggleStatus'])->name('offers.toggle');

    // cars sync from app.moryacars.in facility
    Route::get('morya-cars',[CarListingController::class, 'moryaCarsIndex'])->name('admin.morya-cars.index');
    Route::post('cars/sync', [CarSyncController::class, 'sync'])->name('admin.cars.sync');
    Route::get('car-listings/{id}/edit-synced',[CarListingController::class, 'editSynced'])->name('admin.car-listings.editSynced');
    Route::post('car-listings/{id}/update-synced',[CarListingController::class, 'updateSynced'])->name('admin.car-listings.updateSynced');

    
    Route::get('car-listings/{id}/images',[CarListingController::class, 'images'])->name('admin.car-listings.images');
    Route::post('car-listings/{id}/images',[CarListingController::class, 'storeImages'])->name('admin.car-listings.images.store');
    Route::delete('car-images/{id}',[CarListingController::class, 'deleteImage'])->name('admin.car-images.delete');
    Route::post('car-images/{id}/primary',[CarListingController::class, 'setPrimaryImage'])->name('admin.car-images.primary');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/dev/{cmd}', function ($cmd) {

    $allowed = [
        'storage-link'   => 'storage:link',
        'clear-cache'    => 'cache:clear',
        'clear-config'   => 'config:clear',
        'clear-route'    => 'route:clear',
        'clear-view'     => 'view:clear',
        'optimize'       => 'optimize',
        'optimize-clear' => 'optimize:clear',
    ];

    if (!isset($allowed[$cmd])) {
        return "Invalid command";
    }

    Artisan::call($allowed[$cmd]);

    return "Executed: " . $allowed[$cmd];
});
