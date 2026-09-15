<?php

use App\Http\Controllers\dailycashbookController;
use App\Http\Controllers\DailysheetController;
use App\Http\Controllers\ExpenseReportController;
use App\Http\Controllers\profitController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/reports/profit', [profitController::class, 'index'])->name('reports.profit_loss');
    Route::get('/reports/profit-details', [profitController::class, 'details'])->name('reportProfitDetails');

    Route::get('/reports/dailycashbook', [dailycashbookController::class, 'index'])->name('reportCashbook');
    Route::get('/reports/dailycashbook-data', [dailycashbookController::class, 'details'])->name('reportCashbookData');

    Route::get('/reports/expense', [ExpenseReportController::class, 'index'])->name('reportExpense');
    Route::get('/reports/expense-data', [ExpenseReportController::class, 'details'])->name('reportExpenseData');

    Route::get('/reports/dailysheet', [DailysheetController::class, 'index'])->name('reportDailySheet');
    Route::get('/reports/dailysheet-data', [DailysheetController::class, 'details'])->name('reportDailySheetData');
    Route::post('/reports/dailysheet-store', [DailysheetController::class, 'store'])->name('reportDailySheetStore');
    Route::get('/reports/dailysheet-view/{id}', [DailysheetController::class, 'view'])->name('reportDailySheetView');
});
