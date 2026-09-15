<?php

use App\Http\Controllers\SaleController;
use App\Http\Middleware\ConfirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('sale', SaleController::class);

    Route::get('sales/getproduct/{id}', [SaleController::class, 'getSignleProduct']);
    Route::get('sale/delete/{id}', [SaleController::class, 'destroy'])->name('sales.delete')->middleware(ConfirmPassword::class);

});
