<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ConfirmPassword;
use App\Models\accounts;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\stock;
use App\Models\transactions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /*  public function __construct()
     {
         // Apply middleware to the edit method
         $this->middleware(ConfirmPassword::class)->only('edit');
     } */

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? lastDayOfMonth();
        $supplier = $request->supplier ?? 'all';
        $purchases = purchase::whereBetween('date', [$from, $to])
            ->when($supplier != 'all', function ($query) use ($supplier) {
                $query->where('supplier_id', $supplier);
            })
            ->orderby('id', 'desc')
            ->get();

        $suppliers = accounts::active()->supplier()->get();

        return view('purchase.index', compact('purchases', 'from', 'to', 'supplier', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::orderby('name', 'asc')->get();
        $suppliers = accounts::active()->supplier()->get();
        $accounts = accounts::active()->business()->get();

        return view('purchase.create', compact('products', 'suppliers', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            if ($request->isNotFilled('id')) {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            $ref = getRef();
            $purchase = purchase::create(
                [
                    'supplier_id' => $request->supplier_id,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'inv' => $request->inv,
                    'refID' => $ref,
                ]
            );

            $ids = $request->id;
            $total = 0;
            foreach ($ids as $key => $id) {
                if ($request->qty[$key] > 0) {
                    $qty = $request->qty[$key];
                    $price = $request->price[$key];
                    $amount = $price * $qty;
                    $total += $amount;

                    purchase_details::create(
                        [
                            'purchase_id' => $purchase->id,
                            'product_id' => $id,
                            'price' => $price,
                            'qty' => $qty,
                            'amount' => $amount,
                            'date' => $request->date,
                            'refID' => $ref,
                        ]
                    );
                    createStock($id, $qty, 0, $request->date, "Purchased in $purchase->id", $ref);
                }
            }
            $purchase->update(
                [

                    'total' => $total,
                ]
            );
            if ($request->status == 'paid') {
                createTransaction($request->accountID, $request->date, 0, $total, "Payment of Purchase No. $purchase->id", $ref);
                createTransaction($request->supplier_id, $request->date, $total, $total, "Payment of Purchase No. $purchase->id", $ref);
            } else {
                createTransaction($request->supplier_id, $request->date, 0, $total, "Pending Amount of Purchase No. $purchase->id", $ref);
            }
            DB::commit();

            return back()->with('success', 'Purchase Created');

        } catch (Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(purchase $purchase)
    {
        return view('purchase.view', compact('purchase'));
    }

    public function edit(purchase $purchase)
    {
        $products = products::orderby('name', 'asc')->get();
        $suppliers = accounts::active()->supplier()->get();
        $accounts = accounts::active()->business()->get();

        return view('purchase.edit', compact('products', 'suppliers', 'accounts', 'purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, purchase $purchase)
    {
        try {
            if ($request->isNotFilled('id')) {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            foreach ($purchase->details as $product) {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $purchase->refID)->delete();

            $purchase->update(
                [
                    'supplier_id' => $request->supplier_id,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'inv' => $request->inv,
                ]
            );

            $ids = $request->id;
            $ref = $purchase->refID;

            $total = 0;
            foreach ($ids as $key => $id) {
                if ($request->qty[$key] > 0) {
                    $qty = $request->qty[$key];
                    $price = $request->price[$key];
                    $amount = $price * $qty;
                    $total += $amount;

                    purchase_details::create(
                        [
                            'purchase_id' => $purchase->id,
                            'product_id' => $id,
                            'price' => $price,
                            'qty' => $qty,
                            'amount' => $amount,
                            'date' => $request->date,
                            'refID' => $ref,
                        ]
                    );
                    createStock($id, $qty, 0, $request->date, "Purchased in $purchase->id", $ref);
                }
            }

            $purchase->update(
                [

                    'total' => $total,
                ]
            );

            if ($request->status == 'paid') {
                createTransaction($request->accountID, $request->date, 0, $total, "Payment of Purchase No. $purchase->id", $ref);
                createTransaction($request->supplier_id, $request->date, $total, $total, "Payment of Purchase No. $purchase->id", $ref);
            } else {
                createTransaction($request->supplier_id, $request->date, 0, $total, "Pending Amount of Purchase No. $purchase->id", $ref);
            }
            DB::commit();
            session()->forget('confirmed_password');

            return to_route('purchase.index')->with('success', 'Purchase Updated');
        } catch (Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try {
            DB::beginTransaction();
            $purchase = purchase::find($id);

            foreach ($purchase->details as $product) {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $purchase->refID)->delete();
            $purchase->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('purchase.index')->with('success', 'Purchase Deleted');
        } catch (Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('purchase.index')->with('error', $e->getMessage());
        }
    }

    public function getSignleProduct($id)
    {
        $product = products::find($id);

        return $product;
    }
}
