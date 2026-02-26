<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CarController;
use App\Http\Controllers\Web\SellCarController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function (){
    return view('web.about');
});

Route::middleware('guest:web_portal')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);

    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::get('/buy', [CarController::class, 'index'])->name('buy');
    Route::get('/buy/cars', [CarController::class,'cars'])->name('buy.cars');
    Route::get('/buy/{car}', [CarController::class, 'show'])->name('cars.show');
    Route::get('/sell-your-car', [SellCarController::class, 'index'])->name('sell.car');
    Route::post('/sell/session', [SellCarController::class,'storeSession'])->name('sell.session');
    Route::post('/sell/submit', [SellCarController::class,'submit'])->name('sell.submit');

    Route::get('/sell/models/{id}',[SellCarController::class,'getModels']);



});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:web_portal');