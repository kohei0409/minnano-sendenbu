<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreApplicationController;
use App\Http\Controllers\Admin\StoreApplicationController as AdminStoreApplicationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Store\StaffController;
// 宣伝部機能 controllers
use App\Http\Controllers\PublicStoreController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Store\MenuController;
use App\Http\Controllers\Store\CouponController;
use App\Http\Controllers\Store\ReservationController as StoreReservationController;
use App\Http\Controllers\Store\ReviewReplyController;
use App\Http\Controllers\Store\StoreDetailController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\ReviewModerationController;
use Illuminate\Support\Facades\Route;

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

// Home page
Route::get('/', [PublicStoreController::class, 'home'])->name('home');

// Public routes - Store search and details
Route::get('/stores', [PublicStoreController::class, 'index'])->name('stores.index');
Route::get('/stores/{store}', [PublicStoreController::class, 'show'])->name('stores.show');

// Customer Authentication routes
Route::middleware('guest:customer')->prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\CustomerAuthController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Auth\CustomerRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\CustomerRegisterController::class, 'register']);

    // Password Reset
    Route::get('/forgot-password', [App\Http\Controllers\Auth\CustomerPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\CustomerPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\CustomerPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\CustomerPasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware('auth:customer')->prefix('customer')->name('customer.')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\CustomerAuthController::class, 'logout'])->name('logout');
});

// Public reservation routes (can be used by guests or customers)
Route::get('/stores/{store}/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/stores/{store}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');

// Guest routes - Store application form
Route::get('/store-applications/create', [StoreApplicationController::class, 'create'])
    ->name('store-applications.create');
Route::post('/store-applications', [StoreApplicationController::class, 'store'])
    ->name('store-applications.store');
Route::get('/store-applications/success', [StoreApplicationController::class, 'success'])
    ->name('store-applications.success');

// Authenticated routes with role-based dashboard routing
Route::get('/dashboard', function () {
    $user = auth()->user();

    // Redirect based on role
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'store_owner':
            return redirect()->route('store.dashboard');
        case 'store_staff':
            return redirect()->route('store.dashboard');
        default:
            return view('dashboard');
    }
})->middleware(['auth', 'verified', 'active'])->name('dashboard');

// Profile routes (all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Customer routes (requires customer auth)
Route::middleware('auth:customer')->prefix('customer')->name('customer.')->group(function () {
    // My Page
    Route::get('/mypage', [App\Http\Controllers\Customer\MyPageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/reservations', [App\Http\Controllers\Customer\MyPageController::class, 'reservations'])->name('mypage.reservations');
    Route::get('/mypage/reviews', [App\Http\Controllers\Customer\MyPageController::class, 'reviews'])->name('mypage.reviews');
    Route::get('/mypage/profile/edit', [App\Http\Controllers\Customer\MyPageController::class, 'editProfile'])->name('mypage.edit-profile');
    Route::put('/mypage/profile', [App\Http\Controllers\Customer\MyPageController::class, 'updateProfile'])->name('mypage.update-profile');
    Route::get('/mypage/password/edit', [App\Http\Controllers\Customer\MyPageController::class, 'editPassword'])->name('mypage.edit-password');
    Route::put('/mypage/password', [App\Http\Controllers\Customer\MyPageController::class, 'updatePassword'])->name('mypage.update-password');

    // Reviews
    Route::get('/stores/{store}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/stores/{store}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/stores/{store}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// Admin routes
Route::middleware(['auth', 'verified', 'active', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Store applications management
    Route::get('/applications', [AdminStoreApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [AdminStoreApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/approve', [AdminStoreApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject', [AdminStoreApplicationController::class, 'reject'])->name('applications.reject');

    // User management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/level', [AdminUserController::class, 'updateLevel'])->name('users.update-level');
    Route::patch('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.update-status');

    // Category management
    Route::resource('categories', CategoryController::class);

    // Area management
    Route::resource('areas', AreaController::class);

    // Review moderation
    Route::get('/reviews', [ReviewModerationController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [ReviewModerationController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{review}/approve', [ReviewModerationController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [ReviewModerationController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [ReviewModerationController::class, 'destroy'])->name('reviews.destroy');
});

// Store routes (for both store_owner and store_staff)
Route::middleware(['auth', 'verified', 'active', 'role:store_owner,store_staff'])->prefix('store')->name('store.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('store.dashboard');
    })->name('dashboard');

    // Staff management (only for store_owner)
    Route::middleware('role:store_owner')->group(function () {
        Route::resource('staff', StaffController::class);
    });

    // Store details management
    Route::get('/details', [StoreDetailController::class, 'edit'])->name('details.edit');
    Route::patch('/details', [StoreDetailController::class, 'update'])->name('details.update');
    Route::post('/details/images', [StoreDetailController::class, 'uploadImage'])->name('details.images.upload');
    Route::delete('/details/images/{image}', [StoreDetailController::class, 'deleteImage'])->name('details.images.delete');
    Route::patch('/details/business-hours', [StoreDetailController::class, 'updateBusinessHours'])->name('details.business-hours.update');

    // Menu management
    Route::resource('menus', MenuController::class);

    // Coupon management
    Route::resource('coupons', CouponController::class);

    // Reservation management
    Route::get('/reservations', [StoreReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [StoreReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/confirm', [StoreReservationController::class, 'confirm'])->name('reservations.confirm');
    Route::post('/reservations/{reservation}/cancel', [StoreReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/reservations/{reservation}/complete', [StoreReservationController::class, 'complete'])->name('reservations.complete');

    // Review management
    Route::get('/reviews', [App\Http\Controllers\Store\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [App\Http\Controllers\Store\ReviewController::class, 'show'])->name('reviews.show');

    // Review replies
    Route::post('/reviews/{review}/reply', [ReviewReplyController::class, 'store'])->name('reviews.reply.store');
    Route::patch('/reviews/{review}/reply', [ReviewReplyController::class, 'update'])->name('reviews.reply.update');
    Route::delete('/reviews/{review}/reply', [ReviewReplyController::class, 'destroy'])->name('reviews.reply.destroy');
});

require __DIR__.'/auth.php';
