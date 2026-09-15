<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Middleware\ConfirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('purchase', PurchaseController::class);

    Route::get('purchases/getproduct/{id}', [PurchaseController::class, 'getSignleProduct']);
    Route::get('purchases/delete/{id}', [PurchaseController::class, 'destroy'])->name('purchases.delete')->middleware(ConfirmPassword::class);

});
