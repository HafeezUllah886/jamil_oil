<?php

namespace App\Http\Controllers;

use App\Models\expenses;
use App\Models\sale_details;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    /**
     * Display the business overview dashboard.
     */
    public function index(): View
    {
        $now = Carbon::now();

        // 1. Calculate general overview metrics (Cash balance, stock value, receivables, payables)
        $businessBalance = myBalance();
        $stockVal = stockValue();
        $customerReceivables = customerBalance();
        $supplierPayables = supplierBalance();

        // 2. Calculate current month statistics
        $currentMonthSales = sale_details::whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->sum('amount');

        $currentMonthExpenses = expenses::whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->sum('amount');

        $currentMonthSalesDetails = sale_details::whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->get();

        $currentMonthCogs = 0;
        foreach ($currentMonthSalesDetails as $detail) {
            $currentMonthCogs += $detail->qty * avgPurchasePrice('all', 'all', $detail->product_id);
        }

        $currentMonthProfit = $currentMonthSales - $currentMonthCogs - $currentMonthExpenses;

        // 3. Prepare monthly series data for the last 6 months (including current month)
        $months = [];
        $salesData = [];
        $expensesData = [];
        $profitData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = (clone $now)->subMonths($i);
            $monthLabel = $date->format('M Y');
            $months[] = $monthLabel;

            // Monthly Sales
            $mSales = sale_details::whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');
            $salesData[] = round($mSales, 2);

            // Monthly Expenses
            $mExpenses = expenses::whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');
            $expensesData[] = round($mExpenses, 2);

            // Monthly COGS for profit calculation
            $mSalesDetails = sale_details::whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->get();
            $mCogs = 0;
            foreach ($mSalesDetails as $detail) {
                $mCogs += $detail->qty * avgPurchasePrice('all', 'all', $detail->product_id);
            }

            // Monthly Net Profit
            $mProfit = $mSales - $mCogs - $mExpenses;
            $profitData[] = round($mProfit, 2);
        }

        return view('index', compact(
            'businessBalance',
            'stockVal',
            'customerReceivables',
            'supplierPayables',
            'currentMonthSales',
            'currentMonthExpenses',
            'currentMonthProfit',
            'months',
            'salesData',
            'expensesData',
            'profitData'
        ));
    }
}
