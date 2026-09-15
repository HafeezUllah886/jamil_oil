<?php

namespace App\Http\Controllers;

use App\Models\expenseCategories;
use App\Models\expenses;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function index()
    {
        $categories = expenseCategories::all();

        return view('reports.expense.index', compact('categories'));
    }

    public function details(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $category_id = $request->category ?? 'all';

        $query = expenses::with('category')->whereBetween('date', [$from, $to]);

        if ($category_id != 'all') {
            $query->where('category_id', $category_id);
        }

        $expenses = $query->get();
        $totalExpenses = $expenses->sum('amount');

        return view('reports.expense.details', compact('expenses', 'from', 'to', 'totalExpenses', 'category_id'));
    }
}
