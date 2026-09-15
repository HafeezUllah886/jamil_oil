<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Middleware\ConfirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('products/stock', [ProductsController::class, 'stocks'])->name('product_stock');
    Route::resource('products', ProductsController::class);

    Route::resource('stock-adjustments', StockAdjustmentController::class);
    Route::get('stock-adjustment/delete/{ref}', [StockAdjustmentController::class, 'destroy'])->name('stock-adjustment.delete')->middleware(ConfirmPassword::class);

});
