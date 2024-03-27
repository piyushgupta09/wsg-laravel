<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Fpaipl\Panel\Http\Coordinators\WebPushCoordinator;

Route::middleware(['auth:sanctum'])->prefix('api')->group(function () {

    Route::post('subscribe', [WebPushCoordinator::class, 'store']);
    Route::post('unsubscribe', [WebPushCoordinator::class, 'unsubscribe']);
});



