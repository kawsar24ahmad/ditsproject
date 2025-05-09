<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\CustomVerifyEmailController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;


Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('set-password/{facebook_id}', [NewPasswordController::class, 'setPassword'])
        ->name('password.set');

    Route::post('new-password-store/{facebook_id}', [NewPasswordController::class, 'storeNewPassword'])
        ->name('new.password.store');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // socialite login
    Route::controller(FacebookController::class)->group(function(){
        Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
        Route::get('auth/facebook/callback', 'handleFacebookCallback');
    });

});


// Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
// ->middleware(['signed', 'throttle:6,1'])
// ->name('verification.verify');
Route::get('verify-email/{id}/{hash}', [CustomVerifyEmailController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');


Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');



    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::middleware([ 'verified','role:admin'])->group(function ()  {
        Route::prefix('admin')->group(function ()  {
            Route::get('/', function () {
                return view('admin.dashboard');
            })->name('admin.dashboard');
        });
    });
    Route::middleware([ 'verified','role:user,customer'])->group(function ()  {
        Route::prefix('user')->group(function ()  {
            Route::get('/', function () {
                $services = \App\Models\Service::all();
                return view('user.dashboard', compact('services'));
            })->name('user.dashboard');
        });

    });
    Route::middleware([ 'verified','role:customer'])->group(function ()  {
        Route::prefix('customer')->group(function ()  {
            Route::get('/', function () {
                return view('customer.dashboard');
            })->name('customer.dashboard');
        });
    });
    Route::middleware([ 'verified','role:employee'])->group(function ()  {
        Route::prefix('employee')->group(function ()  {
            Route::get('/', function () {
                return view('employee.dashboard');
            })->name('employee.dashboard');
        });
    });
});
