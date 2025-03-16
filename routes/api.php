<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SocialController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VerifyController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserFeedbackController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::post('/signup', [AuthController::class, 'signup'])->name('api.signup');

    Route::post('/auth/google', [SocialController::class, 'google'])->name('api.auth.google');

    Route::post('/auth/apple', [SocialController::class, 'apple'])->name('api.auth.apple');

    Route::post('/email/resend-verification', [VerifyController::class, 'resend'])->name('api.verify.resend');

    Route::post('/reset-password', [VerifyController::class, 'sendResetLink'])->name('api.reset.password');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'user'])->name('api.user');

    Route::put('/user/update', [UserController::class, 'update'])->name('api.user.update');

    Route::post('/user/change-password', [UserController::class, 'changePassword'])->name('api.change.password');

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::get('/purchase/active', [PurchaseController::class, 'active'])->name('api.plan.active');

    Route::get('/purchase/history', [PurchaseController::class, 'history'])->name('api.plan.history');

    Route::post('/purchase/add', [PurchaseController::class, 'addPurchase'])->name('api.add.purchase');

    Route::get('/family/members', [FamilyMemberController::class, 'members'])->name('api.family.members');
    
    Route::get('/family/member/{familyMember}', [FamilyMemberController::class, 'show'])->name('api.family.member.show');
    
    Route::post('/family/member', [FamilyMemberController::class, 'store'])->name('api.family.member.store');

    Route::post('/family/{familyMember}/message', [FamilyMemberController::class, 'message'])->name('api.family.message');
    
    Route::put('/family/member/{familyMember}', [FamilyMemberController::class, 'update'])->name('api.family.member.update');

    Route::delete('/family/member/{familyMember}', [FamilyMemberController::class, 'destroy'])->name('api.family.member.destroy');
});

Route::get('/options', [OptionController::class, 'getOptions'])->name('api.options');

Route::get('/products', [ProductController::class, 'products'])->name('api.products');

Route::post('/feedback/store', [UserFeedbackController::class, 'store'])->name('api.feedback.store');
