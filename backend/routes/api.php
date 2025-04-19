<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SalonController;
use App\Http\Controllers\Api\ServiceCategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SpecialistController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    // Public salons
    Route::get('salons', [SalonController::class, 'index']);
    Route::get('salons/{id}', [SalonController::class, 'show']);

    // Public services
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{id}', [ServiceController::class, 'show']);
    Route::get('service-categories', [ServiceCategoryController::class, 'index']);

    // Public specialists
    Route::get('specialists', [SpecialistController::class, 'index']);
    Route::get('specialists/{id}', [SpecialistController::class, 'show']);

    // Reviews
    Route::get('reviews', [ReviewController::class, 'index']);
    Route::get('salons/{salon_id}/reviews', [ReviewController::class, 'salonReviews']);
    Route::get('specialists/{specialist_id}/reviews', [ReviewController::class, 'specialistReviews']);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User
    Route::get('user', [UserController::class, 'profile']);
    Route::put('user', [UserController::class, 'update']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Salons management
    Route::post('salons', [SalonController::class, 'store']);
    Route::put('salons/{id}', [SalonController::class, 'update']);
    Route::delete('salons/{id}', [SalonController::class, 'destroy']);

    // Services management
    Route::post('services', [ServiceController::class, 'store']);
    Route::put('services/{id}', [ServiceController::class, 'update']);
    Route::delete('services/{id}', [ServiceController::class, 'destroy']);

    // Specialists management
    Route::post('specialists', [SpecialistController::class, 'store']);
    Route::put('specialists/{id}', [SpecialistController::class, 'update']);
    Route::delete('specialists/{id}', [SpecialistController::class, 'destroy']);

    // Appointments
    Route::get('appointments', [AppointmentController::class, 'index']);
    Route::post('appointments', [AppointmentController::class, 'store']);
    Route::get('appointments/{id}', [AppointmentController::class, 'show']);
    Route::put('appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('appointments/{id}', [AppointmentController::class, 'destroy']);
    Route::get('salons/{salon_id}/available-slots', [AppointmentController::class, 'getAvailableSlots']);

    // Reviews
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::put('reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);

    // Admin routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::put('users/{id}', [UserController::class, 'adminUpdate']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);

        Route::put('reviews/{id}/approve', [ReviewController::class, 'approve']);
        Route::put('reviews/{id}/hide', [ReviewController::class, 'hide']);
    });
});
