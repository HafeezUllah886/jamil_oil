<?php

namespace App\Http\Controllers;

use App\Models\dailysheet;
use App\Models\dailysheet_detail;
use App\Models\products;
use App\Models\sale_details;
use App\Models\stock;
use Illuminate\Http\Request;

class DailysheetController extends Controller
{
    public function index()
    {
        $dailysheets = dailysheet::orderBy('id', 'desc')->get();

        return view('reports.dailysheet.index', compact('dailysheets'));
    }

    public function details(Request $request)
    {
        $from_date = $request->from;
        $to_date = $request->to;

        $products = products::active()->get();

        foreach ($products as $product) {
            $opening_stock = getStockOnTime($product->id, $from_date);
            $closing_stock = getStockOnTime($product->id, $to_date);

            $stock_in = stock::where('product_id', $product->id)->whereBetween('created_at', [$from_date, $to_date])->sum('cr');
            $stock_out = stock::where('product_id', $product->id)->whereBetween('created_at', [$from_date, $to_date])->sum('db');

            $sold_qty = sale_details::where('product_id', $product->id)->whereBetween('date', [$from_date, $to_date])->sum('qty');
            $amount = sale_details::where('product_id', $product->id)->whereBetween('date', [$from_date, $to_date])->sum('amount');

            $paid = sale_details::where('product_id', $product->id)->whereBetween('date', [$from_date, $to_date])->whereHas('sale', function ($q) {
                $q->where('status', 'paid');
            })->sum('amount');

            $pending = sale_details::where('product_id', $product->id)->whereBetween('date', [$from_date, $to_date])->whereHas('sale', function ($q) {
                $q->where('status', 'pending');
            })->sum('amount');

            $product->opening_stock = $opening_stock;
            $product->closing_stock = $closing_stock;
            $product->stock_in = $stock_in;
            $product->stock_out = $stock_out;
            $product->sold_qty = $sold_qty;
            $product->amount = $amount;
            $product->paid = $paid;
            $product->pending = $pending;
        }

        return view('reports.dailysheet.details', compact('products', 'from_date', 'to_date'));
    }

    public function store(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $dailysheet = dailysheet::create([
            'from' => $from_date,
            'to' => $to_date,
        ]);

        foreach ($request->product_id as $key => $product_id) {
            $start_meter = $request->start_meter[$key];
            $end_meter = $request->end_meter[$key];
            $checking_meter = $request->checking_meter[$key];

            dailysheet_detail::create([
                'dailysheet_id' => $dailysheet->id,
                'product_id' => $product_id,
                'openingMeter' => $start_meter,
                'closingMeter' => $end_meter,
                'totalLtr' => $end_meter - $start_meter,
                'checkingLtr' => $checking_meter,
            ]);
        }

        return redirect()->route('reportDailySheet', ['from' => $from_date, 'to' => $to_date])
            ->with('success', 'Daily sheet updated successfully!');
    }

    public function view($id)
    {
        $dailysheet = dailysheet::find($id);
        $from_date = $dailysheet->from;
        $to_date = $dailysheet->to;

        $products = products::active()->get();

        foreach ($products as $product) {
            $opening_stock = getStockOnTime($product->id, $from_date);
            $closing_stock = getStockOnTime($product->id, $to_date);

            $stock_in = stock::where('product_id', $product->id)->whereBetween('created_at', [$from_date, $to_date])->sum('cr');
            $stock_out = stock::where('product_id', $product->id)->whereBetween('created_at', [$from_date, $to_date])->sum('db');

            $sold_qty = sale_details::where('product_id', $product->id)->whereBetween('date', [$from_date, $to_date])->sum('qty');
            $amount = sale_details::where('product_id', $product->id)->whereBetween('date', [$from_date, $to_date])->sum('amount');

            $paid = sale_details::where('product_id', $product->id)->whereBetween('date', [$from_date, $to_date])->whereHas('sale', function ($q) {
                $q->where('status', 'paid');
            })->sum('amount');

            $pending = sale_details::where('product_id', $product->id)->whereBetween('date', [$from_date, $to_date])->whereHas('sale', function ($q) {
                $q->where('status', 'pending');
            })->sum('amount');

            $product->opening_stock = $opening_stock;
            $product->closing_stock = $closing_stock;
            $product->stock_in = $stock_in;
            $product->stock_out = $stock_out;
            $product->sold_qty = $sold_qty;
            $product->amount = $amount;
            $product->paid = $paid;
            $product->pending = $pending;
            $product->meter_start = $dailysheet->details->where('product_id', $product->id)->first()->openingMeter ?? '-';
            $product->meter_end = $dailysheet->details->where('product_id', $product->id)->first()->closingMeter ?? '-';
            $product->meter_checking = $dailysheet->details->where('product_id', $product->id)->first()->checkingLtr ?? '-';
            $product->meter_total = $dailysheet->details->where('product_id', $product->id)->first()->totalLtr ?? '-';
        }

        return view('reports.dailysheet.view', compact('products', 'from_date', 'to_date'));
    }
}
