<?php

namespace App\Http\Controllers;

use App\Models\expenseCategories;
use Illuminate\Http\Request;

class ExpenseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cats = expenseCategories::orderBy('name', 'asc')->get();

        return view('finance.expense.category', compact('cats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        expenseCategories::create($request->all());

        return back()->with('msg', 'Category Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(expenseCategories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(expenseCategories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        expenseCategories::find($id)->update($request->all());

        return back()->with('msg', 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        expenseCategories::find($id)->delete();

        return back()->with('msg', 'Category Deleted');
    }
}
