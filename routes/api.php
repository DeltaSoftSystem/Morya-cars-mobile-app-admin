<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\CarController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\AuctionContoller;
use App\Http\Controllers\Api\V1\CarFilterController;
use App\Http\Controllers\Api\V1\CityController;
use App\Services\FirebaseService;
use App\Http\Controllers\Api\V1\FirebaseAuctionResultController;
use App\Http\Controllers\Api\V1\SubscriptionPaymentController;
use App\Http\Controllers\Api\V1\RazorpayWebhookController;
use App\Http\Controllers\Api\V1\AuctionDepositController;
use App\Http\Controllers\Api\V1\DealerController;
use App\Http\Controllers\Api\V1\ServiceRequestController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\ServiceItemController;
use App\Http\Controllers\Api\V1\AccessoryController;
use App\Http\Controllers\Api\V1\AccessoryBookingController;
use App\Http\Controllers\Api\V1\OfferController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 🔓 Public Routes (NO AUTH / NO SUBSCRIPTION)
    |--------------------------------------------------------------------------
    */
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('register', [AuthController::class, 'register']);

    // Public Catalog
    Route::get('car-makes', [CarController::class, 'getMakes']);
    Route::get('car-models', [CarController::class, 'getModels']);
    Route::get('car-listings', [CarController::class, 'indexListings']);
    Route::get('car-listings/{id}', [CarController::class, 'showListing']);
    Route::get('car-listings/user/{userid}', [CarController::class, 'userwiesListing']);

    // Filters
    Route::get('/filters', [CarFilterController::class, 'getFilters']);
    Route::get('/sort-options', [CarFilterController::class, 'getSortOptions']);
    Route::get('filters/car-listings', [CarFilterController::class, 'getCarListings']);

    // Cities
    Route::get('/cities', [CityController::class, 'index']);
    Route::get('/cities/{id}', [CityController::class, 'show']);
    Route::get('/cities-search', [CityController::class, 'search']);
    Route::get('/cities-paginated', [CityController::class, 'paginated']);

    // Subscription Plans (Public)
    Route::get('/subscription/plans', [SubscriptionController::class, 'plans']);
    Route::get('/subscription/active/{user_id}', [SubscriptionController::class, 'activePlan']);

    // Auction (Public View)
    Route::get('auctions', [AuctionContoller::class, 'index']);
    Route::get('auctions/{id}', [AuctionContoller::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | 🔐 Authenticated Routes (LOGIN REQUIRED)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        // Profile
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('/profile/update', [AuthController::class, 'updateProfile']);
        Route::post('/send-email-otp', [AuthController::class, 'sendProfileEmailOtp']);
        Route::post('/profile/verify-otp', [AuthController::class, 'verifyProfileOtp']);

        //DEALER 
        Route::post('dealer/become', [DealerController::class, 'becomeDealer']);
        Route::post('dealer/kyc-upload', [DealerController::class, 'uploadKyc']);
        Route::get('dealer/status', [DealerController::class, 'status']);

        // Subscription Info (for app banner)
        Route::get('/my-subscription', [SubscriptionController::class, 'mySubscription']);

        // Payments (MUST remain accessible after expiry)
        Route::post('/subscription/order', [SubscriptionPaymentController::class, 'createOrder']);
        Route::post('/subscription/verify', [SubscriptionPaymentController::class, 'verifyPayment']);

        // User Deposits
        Route::get('auction/deposits', [AuctionDepositController::class,'myDeposits']);

        

        //Service Request
        Route::post('/service-request', [ServiceRequestController::class, 'store']);
    });


    // 🚗 Seller Actions
        Route::post('cars', [CarController::class, 'store']);
        Route::post('car-listings', [CarController::class, 'storeListing']);
        Route::put('car-listings/{id}', [CarController::class, 'update']);
        Route::post('car-images/{id}', [CarController::class, 'storeImage']);
        Route::delete('car-images/{id}', [CarController::class, 'deleteImage']);
        Route::post('car-listings/{listing_id}/images/{image_id}/set-primary', [CarController::class, 'setPrimary']);

        // 🛒 Buyer Actions
        Route::post('bookings', [BookingController::class, 'store']);
        Route::post('bookings/{id}/upload-proof', [BookingController::class, 'uploadProof']);
        Route::get('bookings/{userId}', [BookingController::class, 'show']); 
        /* bookings/{userId}
            required auth tocken Headers:
            Authorization: Bearer YOUR_TOKEN
            Accept: application/json
            */
            
    /*
    |--------------------------------------------------------------------------
    | 🔒 Subscription Protected Routes (ACTIVE PLAN REQUIRED)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum', 'check.subscription'])->group(function () {
        // 🔥 Auction Actions
        Route::post('auctions/{id}/bid', [AuctionContoller::class, 'placeBid']);
        Route::post('auction/{auction}/deposit', [AuctionDepositController::class,'store']);
        Route::post('auction/{auction}/deposit/upload-proof', [AuctionDepositController::class,'uploadProof']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
    // 🚗 Seller Actions
        Route::post('cars', [CarController::class, 'store']);
        Route::post('car-listings', [CarController::class, 'storeListing']);
        Route::put('car-listings/{id}', [CarController::class, 'update']);
        Route::post('car-images/{id}', [CarController::class, 'storeImage']);
        Route::delete('car-images/{id}', [CarController::class, 'deleteImage']);
        Route::post('car-listings/{listing_id}/images/{image_id}/set-primary', [CarController::class, 'setPrimary']);

        // 🛒 Buyer Actions
        Route::post('bookings', [BookingController::class, 'store']);
        Route::post('bookings/{id}/upload-proof', [BookingController::class, 'uploadProof']);
        Route::get('bookings/{userId}', [BookingController::class, 'show']); 

        Route::post('accessories/book', [AccessoryBookingController::class, 'store']);
    });
        /* bookings/{userId}
            required auth tocken Headers:
            Authorization: Bearer YOUR_TOKEN
            Accept: application/json
            */
        //Service List
        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/services/{service}/items',[ServiceItemController::class, 'index']);

        //Accessories
        Route::get('accessory-categories', [AccessoryController::class, 'categories']);
        Route::get('accessories', [AccessoryController::class, 'index']);
        Route::get('accessories/{id}', [AccessoryController::class, 'show']);
        
        //offers
        Route::get('/offers', [OfferController::class, 'allActive']);
        Route::get('/offers/{module}', [OfferController::class, 'byModule']);

    /*
    |--------------------------------------------------------------------------
    | 🔧 System / Webhooks
    |--------------------------------------------------------------------------
    */
    Route::post('/razorpay/webhook', [RazorpayWebhookController::class, 'handle']);

    Route::post('firebase/auction-result', [FirebaseAuctionResultController::class, 'store']);

    Route::get('/firebase-test', function (FirebaseService $firestore) {
        $firestore->setDocument('laravel_test', 'ping', [
            'status' => 'connected',
            'time' => now()->toDateTimeString(),
        ]);

        return 'REST Firestore OK!';
    });

});