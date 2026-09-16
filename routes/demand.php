<?php

use App\Http\Controllers\DemandController;
use App\Http\Middleware\ConfirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('demand', DemandController::class);
    Route::get('demand/delete/{id}', [DemandController::class, 'destroy'])->name('demand.delete')->middleware(ConfirmPassword::class);
    Route::get('demand/deliver/{demand}', [DemandController::class, 'deliverForm'])->name('demand.deliver');
    Route::post('demand/deliver/{demand}', [DemandController::class, 'deliverStore'])->name('demand.deliverStore');

});
