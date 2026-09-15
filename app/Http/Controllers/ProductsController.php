<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\stock;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = products::all();

        return view('product_mgmt.products', compact('items'));
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
        $request->validate(
            [
                'name' => 'unique:products,name',
            ],
            [
                'name.unique' => 'Product already Existing',
            ]
        );

        products::create($request->all());

        return back()->with('success', 'Product Created');
    }

    public function stocks()
    {
        $products = products::active()->get();

        return view('product_mgmt.stock', compact('products'));
    }

    public function show(Request $request)
    {
        $product = products::find($request->id);
        $id = $product->id;
        $from = $request->from;
        $to = $request->to;

        $stocks = stock::where('product_id', $id)->whereBetween('date', [$from, $to])->get();

        $pre_cr = stock::where('product_id', $id)->whereDate('date', '<', $from)->sum('cr');
        $pre_db = stock::where('product_id', $id)->whereDate('date', '<', $from)->sum('db');

        $cur_cr = stock::where('product_id', $id)->sum('cr');
        $cur_db = stock::where('product_id', $id)->sum('db');

        $pre_balance = $pre_cr - $pre_db;
        $cur_balance = $cur_cr - $cur_db;

        return view('product_mgmt.stock_details', compact('product', 'pre_balance', 'cur_balance', 'stocks', 'from', 'to'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'unique:products,name,'.$id,
            ],
            [
                'name.unique' => 'Product already Existing',
            ]
        );

        $product = products::find($id);
        $product->update($request->all());

        return back()->with('success', 'Product Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(products $products)
    {
        //
    }

    public function ajaxCreate(Request $request)
    {
        $check = products::where('name', $request->name)->count();
        if ($check > 0) {
            return response()->json(
                ['response' => 'Exists']
            );
        }
        $product = products::create($request->all());

        return response()->json(
            ['response' => $product->id]
        );
    }
}
