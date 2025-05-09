<?php

use App\Models\FacebookPage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ServicePurchaseController;
use App\Http\Controllers\Admin\ServiceTaskController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\Admin\FacebookPageController;
use App\Http\Controllers\Admin\ServiceAssignController;
use App\Http\Controllers\Employee\ServiceAssignController as EmployeeServiceAssignController;
use App\Http\Controllers\Admin\WalletTransactionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\User\WalletController as UserWalletController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\ServiceController as UserServiceController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\FacebookController as CustomerFacebookController;
use App\Http\Controllers\User\ServiceAssignedController;
use App\Http\Controllers\User\WalletTransactionController as UserWalletTransactionController;






Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (in_array($role, ['user', 'customer'])) {
            return redirect()->route('user.dashboard');
        }elseif($role === "employee"){
            return redirect()->route('employee.dashboard');
        }
    }

    return redirect()->route('login'); // or login page
});

Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function ()  {
    Route::prefix('admin')->group(function ()  {
        Route::resource('admin_users', AdminUserController::class);
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
        Route::patch('/profile/changePhoto', [AdminProfileController::class, 'changePhoto'])->name('admin.profile.changePhoto');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');
        Route::resource('admin_categories',CategoryController::class);
        Route::resource('services', ServiceController::class)->names('admin.services');

        Route::get('/wallet-transactions', [WalletTransactionController::class, 'index'])->name('admin.wallet.transactions');
        Route::post('/wallet-transactions/{transaction}', [WalletTransactionController::class, 'update'])->name('admin.wallet.transactions.update');
        Route::get('/service-purchases', [ServicePurchaseController::class, 'index'])->name('admin.service.purchases');
        Route::put('/service/purchase/{id}/approve', [ServicePurchaseController::class, 'approve'])->name('admin.service.purchase.approve');
        Route::put('/service/purchase/{id}/reject', [ServicePurchaseController::class, 'reject'])->name('admin.service.purchase.reject');
        Route::delete('/service/purchase/{id}', [ServicePurchaseController::class, 'destroy'])->name('admin.service.purchase.destroy');
        Route::get('/facebook-ad-requests', [ServicePurchaseController::class, 'facebookAdRequests'])->name('admin.facebook-ad-requests');
        Route::resource('facebook-pages', FacebookPageController::class)->names('admin.facebook-pages');
        Route::put('facebook-pages/{id}/toggle-status', [FacebookPageController::class, 'toggleStatus'])->name('admin.facebook-pages.toggleStatus');

        Route::get('/site-settings', [SettingController::class, 'edit'])->name('admin.site-settings.edit');
        Route::post('/site-settings', [SettingController::class, 'update'])->name('admin.site-settings.update');

        Route::resource('service-assigns', ServiceAssignController::class)->names('admin.service_assigns');


        Route::post('/services/{service}/tasks', [ServiceTaskController::class, 'store'])->name('admin.tasks.store');
        Route::patch('/tasks/{task}/toggle', [ServiceTaskController::class, 'toggle'])->name('admin.tasks.toggle');
        Route::delete('/tasks/{task}', [ServiceTaskController::class, 'destroy'])->name('admin.tasks.destroy');



    });
});


Route::middleware([ 'auth','role:user,customer'])->group(function ()  {
    Route::prefix('user')->group(function ()  {
        Route::get('/profile', [UserProfileController::class, 'edit'])->name('user.profile.edit');
        Route::patch('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
        Route::patch('/profile/changePhoto', [UserProfileController::class, 'changePhoto'])->name('user.profile.changePhoto');
        Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('user.profile.destroy');
        Route::get('/wallet', [UserWalletController::class, 'index'])->name('user.wallet.index');
        Route::post('/wallet/recharge', [UserWalletController::class, 'recharge'])->name('user.wallet.recharge');
        Route::get('/transactions', [UserWalletTransactionController::class, 'index'])->name('user.transactions.index');
        Route::get('/services/{id}', [UserServiceController::class, 'show'])->name('user.services.show');
        Route::post('/services/facebook-ad/buy', [UserServiceController::class, 'buyFacebookAdService'])->name('user.services.facebook_ad.buy');
        Route::get('/service-assigns/{id}', [ServiceAssignedController::class, 'show'])->name('user.service_assigns.show');
        Route::get('/service-assigns/{id}/generate', [ServiceAssignedController::class, 'invoiceGenerate'])->name('user.service_assigns.invoiceGenerate');
        Route::get('/service-assigns/{id}/generate/pdf', [ServiceAssignedController::class, 'invoiceGeneratePdf'])->name('user.service_assigns.invoiceGeneratePdf');
    });
});

Route::middleware([ 'auth','role:customer'])->group(function ()  {
    Route::prefix('customer')->group(function ()  {
        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
        Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
        Route::patch('/profile/changePhoto', [CustomerProfileController::class, 'changePhoto'])->name('customer.profile.changePhoto');
        Route::delete('/profile', [CustomerProfileController::class, 'destroy'])->name('customer.profile.destroy');
        Route::get('/videos/{id}', [CustomerFacebookController::class, 'videos'])->name('facebook.videos');
        Route::get('/posts/{id}', [CustomerFacebookController::class, 'posts'])->name('facebook.posts');
    });

});
Route::middleware([ 'auth','role:employee'])->group(function ()  {
    Route::prefix('employee')->group(function ()  {
        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('employee.profile.edit');
        Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('employee.profile.update');
        Route::patch('/profile/changePhoto', [CustomerProfileController::class, 'changePhoto'])->name('employee.profile.changePhoto');
        Route::delete('/profile', [CustomerProfileController::class, 'destroy'])->name('employee.profile.destroy');
        Route::resource('service-assigns', EmployeeServiceAssignController::class)->names('employee.service_assigns');
        Route::patch('/tasks/{task}/toggle', [EmployeeServiceAssignController::class, 'toggle'])->name('employee.tasks.toggle');
    });

});

// SSLCOMMERZ Start
Route::middleware(['auth'])->group(function () {
    Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
    Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);
    Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
    Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);
});
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);

require __DIR__.'/auth.php';
