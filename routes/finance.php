<?php

use App\Http\Controllers\AccountAdjustmentController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\IssuePaymentsController;
use App\Http\Controllers\PaymentReceivingController;
use App\Http\Controllers\TransferController;
use App\Http\Middleware\ConfirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('account', AccountsController::class);

    Route::resource('receivings', PaymentReceivingController::class);
    Route::get('receiving/delete/{ref}', [PaymentReceivingController::class, 'destroy'])->name('receiving.delete')->middleware(ConfirmPassword::class);

    Route::resource('issue', IssuePaymentsController::class);
    Route::get('issue/delete/{ref}', [IssuePaymentsController::class, 'destroy'])->name('issue.delete')->middleware(ConfirmPassword::class);

    Route::resource('transfers', TransferController::class);
    Route::get('transfer/delete/{ref}', [TransferController::class, 'destroy'])->name('transfer.delete')->middleware(ConfirmPassword::class);

    Route::resource('adjustments', AccountAdjustmentController::class);
    Route::get('adjustment/delete/{ref}', [AccountAdjustmentController::class, 'destroy'])->name('adjustment.delete')->middleware(ConfirmPassword::class);

    Route::resource('expensesCategories', ExpenseCategoriesController::class);
    Route::resource('expenses', ExpensesController::class);
    Route::get('expense/delete/{ref}', [ExpensesController::class, 'delete'])->name('expense.delete')->middleware(ConfirmPassword::class);

    Route::get('/accountbalance/{id}', function ($id) {
        // Call your Laravel helper function here
        $result = getAccountBalance($id);

        return response()->json(['data' => $result]);
    });
});
