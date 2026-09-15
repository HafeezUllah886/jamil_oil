<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\stock;
use App\Models\stockTransfer;
use App\Models\stockTransferDetails;
use App\Models\warehouses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transfers = stockTransfer::orderBy('id', 'desc')->get();

        return view('stock.transfer.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::orderby('name', 'asc')->get();
        $warehouses = warehouses::all();

        return view('stock.transfer.create', compact('products', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }

            DB::beginTransaction();
            $ref = getRef();
            $transfer = stockTransfer::create(
                [
                  'fromID'      => $request->fromID,
                  'toID'        => $request->toID,
                  'date'        => $request->date,
                  'notes'       => $request->notes,
                  'userID'      => auth()->user()->id,
                  'refID'       => $ref,
                ]
            );

            $ids = $request->id;

            foreach($ids as $key => $id)
            {
                $qty = $request->qty[$key];
                stockTransferDetails::create(
                    [
                        'transferID'    => $transfer->id,
                        'productID'     => $id,
                        'qty'           => $qty,
                        'refID'         => $ref,
                    ]
                );
                $warehouseFrom = warehouses::find($request->fromID);
                $warehouseTo = warehouses::find($request->toID);
                createStock($id,0, $qty, $request->date, "Transfered to $warehouseTo->name", $ref, $request->fromID);
                createStock($id,$qty, 0, $request->date, "Transfered from $warehouseFrom->name", $ref, $request->toID);
            }
           DB::commit();
            return to_route('stockTransfer.index')->with('success', "Stock Transfer Created");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
    public function show($id)
    {
        $transfer = stockTransfer::find($id);

        return view('stock.transfer.view', compact('transfer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stockTransfer $stockTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stockTransfer $stockTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try
        {
            DB::beginTransaction();
            $transfer = stockTransfer::find($id);
            foreach($transfer->details as $product)
            {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            $transfer->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('stockTransfer.index')->with('success', "Stock Transfer Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return to_route('stockTransfer.index')->with('error', $e->getMessage());
        }
    }
}
