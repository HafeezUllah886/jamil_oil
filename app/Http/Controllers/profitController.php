<?php

namespace App\Http\Controllers;

use App\Models\expenses;
use App\Models\products;
use App\Models\sale_details;
use Illuminate\Http\Request;

class profitController extends Controller
{
    public function index()
    {
        return view('reports.profit.index');
    }

    public function details(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $products = products::all();
        $data = [];
        foreach ($products as $product) {
            $purchaseRate = avgPurchasePrice($from, $to, $product->id);
            $saleRate = avgSalePrice($from, $to, $product->id);
            $sold = sale_details::where('product_id', $product->id)->whereBetween('date', [$from, $to])->sum('qty');
            $ppu = $saleRate - $purchaseRate;
            $profit = $ppu * $sold;
            $stock = getStock($product->id);
            $stockValue = productStockValue($product->id);

            $data[] = ['name' => $product->name, 'purchaseRate' => $purchaseRate, 'saleRate' => $saleRate, 'sold' => $sold, 'ppu' => $ppu, 'profit' => $profit, 'stock' => $stock, 'stockValue' => $stockValue];
        }

        $expenses = expenses::whereBetween('date', [$from, $to])->sum('amount');

        return view('reports.profit.details', compact('from', 'to', 'data', 'expenses'));
    }
}
