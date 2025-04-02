<?php

use Illuminate\Http\Request;
use App\Livewire\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin-home');
    }
    return redirect()->route('login');
})->name('home');

Route::get('/migrate-fresh', function () {
    Artisan::call('migrate:fresh --seed');
    return 'Migrated and seeded';
});
Route::get('/migrate', function () {
    Artisan::call('migrate');
    return 'Migrated';
});
Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return 'Cache Cleared';
});
Route::get('/optimize', function () {
    Artisan::call('optimize');
    return 'Cache Cleared';
});

Route::get('email/verify/view/{id}/{hash}', [VerifyController::class, 'viewEmail'])->name('email.verification.view');
Route::get('password/reset/view/{email}/{token}', [VerifyController::class, 'viewInBrowser'])->name('password.reset.view');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'LoginForm'])->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:6,1');

    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify/{id}/{hash}', [VerifyController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->withoutMiddleware(['auth'])->name('verification.verify');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

require __DIR__ . '/admin.php';

Route::get('/send-test-email', function () {
    \Illuminate\Support\Facades\Mail::raw('This is a test email', function ($message) {
        $message->to('sameedhassan22@gmail.com')
            ->subject('Test Email');
    });

    return 'Test email sent';
});
