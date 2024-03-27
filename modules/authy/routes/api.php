<?php

use Illuminate\Support\Facades\Route;
use Fpaipl\Authy\Http\Coordinators\AuthCoordinator;
use Fpaipl\Authy\Http\Coordinators\AccountCoordinator;
use Fpaipl\Authy\Http\Coordinators\AddressCoordinator;
use Fpaipl\Authy\Http\Coordinators\PincodeCoordinator;
use Fpaipl\Authy\Http\Coordinators\PromotionCoordinator;
use Fpaipl\Authy\Http\Coordinators\NotificationCoordinator;

Route::middleware(['api', 'throttle:60,1'])->prefix('api')->group(function () {

    // 1. Auth
    if (config('authy.registeration.website')) {
        Route::post('register', [AuthCoordinator::class, 'register']);
    }
    Route::post('send-login-otp', [AuthCoordinator::class, 'sendLoginOtp']);
    Route::post('login', [AuthCoordinator::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [AuthCoordinator::class, 'logout']);
        Route::post('email/verification', [AuthCoordinator::class, 'emailVerification']);
        Route::post('email/verify', [AuthCoordinator::class, 'verifyOtp']);
        Route::post('validate-session', [AuthCoordinator::class, 'validateSession']);

        Route::middleware('verified')->group(function () {

            // 2. Profile & Password
            Route::post('update-user', [AuthCoordinator::class, 'updateUser']);
            Route::post('update-password', [AuthCoordinator::class, 'updateNewPassword']);
            Route::get('user-profile', [AuthCoordinator::class, 'userProfile']);
            Route::post('upload-profile-image', [AuthCoordinator::class, 'updateProfileImage']);
            
            // 3. Account KYC
            Route::get('authy-static-data', [AccountCoordinator::class, 'staticData']);
            Route::get('user-account', [AccountCoordinator::class, 'userAccount']);
            Route::post('account-verification', [AccountCoordinator::class, 'accountVerification']);
            Route::post('edit-kyc-form', [AccountCoordinator::class, 'editKycForm']);
            Route::post('skip-account-verification', [AccountCoordinator::class, 'skipAccountVerification']);
            
            Route::middleware('approved')->group(function () {

                // 4. Address
                Route::apiResource('addresses', AddressCoordinator::class);
                Route::post('validate-pincode', [PincodeCoordinator::class, 'validatePincode']);

                // 5. Notifications
                Route::get('notifications/pusher', [NotificationCoordinator::class, 'pusherAuth']);
                Route::get('notifications', [NotificationCoordinator::class, 'index']);
                Route::get('promotions', [PromotionCoordinator::class, 'index']);
                // Route::get('notifications/unread', [NotificationCoordinator::class, 'unread']);
                // Route::post('notifications/read', [NotificationCoordinator::class, 'markAllRead']);
                Route::post('notifications/{notification}/read', [NotificationCoordinator::class, 'markRead']);

                Route::get('support-page', function () {
                    return json_encode([
                        'message' => 'success',
                        'data' => config('settings.support')
                    ]);
                });
            }); 
        });

    });

});
