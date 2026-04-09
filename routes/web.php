<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Mime\Address;

Route::get('/', [LandingPageController::class, 'index']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/collections', [LandingPageController::class, 'collections'])->name('collections.index');
Route::get('/collections/{slug}', [LandingPageController::class, 'show'])->name('collections.show');

Route::post('/midtrans/callback', [App\Http\Controllers\MidtransController::class, 'callback']);
Route::get('/order/{order}/payment-status', [CheckoutController::class, 'checkPaymentStatus'])->middleware('auth');
Route::post('/order/{order}/review', [OrderHistoryController::class, 'submitReview'])->middleware('auth')->name('order.review.store');

Route::middleware(['auth'])->group(function () {

    // Categories
    Route::prefix('categories')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    // Brands
    Route::prefix('brands')->controller(BrandController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    // Coupons
    Route::prefix('coupons')->controller(CouponController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::prefix('customers')->controller(CustomerController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::prefix('reviews')->controller(ReviewController::class)->group(function () {
        Route::get('/', 'index');
        Route::patch('/{id}/toggle-verify', 'toggleVerify');
        Route::delete('/{id}', 'destroy');
    });

    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index');
        Route::put('/update', 'updateProfile')->name('profile.update');
        Route::put('/password', 'updatePassword')->name('profile.password');
    });

    // Products
    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');

        // Product Images
        Route::delete('/{id}/images/{imageId}', 'destroyImage');
        Route::post('/{id}/images/primary/{imageId}', 'setPrimaryImage');

        // Product Variants
        Route::post('/{id}/variants', 'storeVariant');
        Route::put('/{id}/variants/{variantId}', 'updateVariant');
        Route::delete('/{id}/variants/{variantId}', 'destroyVariant');
    });

    Route::prefix('orders')->controller(OrderController::class)->group(function () {

        Route::get('/',        'index')->name('orders.index');
        Route::get('/{id}',    'show')->name('orders.show');
        Route::get('/{id}/api', 'showApi')->name('orders.api');
        Route::patch('/{id}/status', 'updateStatus')->name('orders.status');
        Route::patch('/{id}/resi', 'updateResi')->name('orders.resi');
    });

    Route::post('/reviews/{id}/verify', [OrderController::class, 'verify'])->name('reviews.verify');

    // Cart
    Route::prefix('cart')->controller(CartController::class)->group(function () {
        Route::get('/',         'index')->name('cart.index');
        Route::post('/add',     'store')->name('cart.store');
        Route::patch('/update/{id}', 'update')->name('cart.update');
        Route::delete('/delete/{id}', 'destroy')->name('cart.destroy');
    });

    // Checkout
    Route::prefix('checkout')->controller(CheckoutController::class)->group(function () {
        Route::get('/',         'index')->name('checkout.index');
        Route::post('/set-address', 'setAddress')->name('checkout.set-address');
        Route::post('/check-ongkir', 'checkOngkir')->name('checkout.check-ongkir');
        Route::get('/search-destination', 'searchDestination')->name('checkout.search-destination');
        Route::post('/apply-coupon',     'applyCoupon')->name('checkout.apply-coupon');
        Route::post('/remove-coupon',    'removeCoupon')->name('checkout.remove-coupon');
        Route::post('/',    'store')->name('checkout.store');
    });

    Route::prefix('addresses')->controller(AddressController::class)->group(function () {
        Route::post('/',         'store')->name('addresses.store');
    });

    Route::prefix('order-history')->controller(OrderHistoryController::class)->group(function () {
        Route::get('/', 'index')->name('order.history');
        Route::get('/{id}', 'show')->name('order.history.show');
        Route::patch('/{id}/complete', 'markAsCompleted')->name('order.history.complete');
    });
});
