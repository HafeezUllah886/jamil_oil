<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ConfirmPassword;
use App\Models\accounts;
use App\Models\attendants;
use App\Models\products;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\salePayments;
use App\Models\stock;
use App\Models\transactions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
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
        $customer = $request->customer ?? 'all';
        $sales = sale::whereBetween('date', [$from, $to])
            ->when($customer != 'all', function ($query) use ($customer) {
                $query->where('customer_id', $customer);
            })
            ->orderby('id', 'desc')
            ->get();

        $customers = accounts::active()->customer()->get();

        return view('sale.index', compact('sales', 'from', 'to', 'customer', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::orderby('name', 'asc')->get();
        $customers = accounts::active()->customer()->get();
        $accounts = accounts::active()->business()->get();
        $attendants = attendants::active()->get();

        return view('sale.create', compact('products', 'customers', 'accounts', 'attendants'));
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
            $sale = sale::create(
                [
                    'customer_id' => $request->customer_id,
                    'attendant_id' => $request->attendant,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'status' => $request->status,
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

                    sale_details::create(
                        [
                            'sale_id' => $sale->id,
                            'product_id' => $id,
                            'price' => $price,
                            'qty' => $qty,
                            'amount' => $amount,
                            'date' => $request->date,
                            'refID' => $ref,
                        ]
                    );
                    createStock($id, 0, $qty, $request->date, "Sold in $sale->id", $ref);
                }
            }
            $sale->update(
                [
                    'total' => $total,
                ]
            );
            if ($request->status == 'paid') {
                $account_ids = $request->account_id;
                $payment_amount = $request->payment_amount;
                $payment_notes = $request->payment_notes;
                foreach ($account_ids as $key => $account_id) {
                    if ($payment_amount[$key] > 0) {
                        $account = accounts::find($account_id);
                        createTransaction($account->id, $request->date, $payment_amount[$key], 0, "Payment of Sale # $sale->id Remarks".$payment_notes[$key], $ref);

                        salePayments::create(
                            [
                                'sale_id' => $sale->id,
                                'account_id' => $account_id,
                                'amount' => $payment_amount[$key],
                                'notes' => $payment_notes[$key],
                                'date' => $request->date,
                                'refID' => $ref,
                            ]
                        );
                    }

                }
            } else {
                createTransaction($request->customer_id, $request->date, $total, 0, "Pending Amount of Sale # $sale->id", $ref);
            }
            DB::commit();

            return back()->with('success', 'Sale Created');

        } catch (Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(sale $sale)
    {
        return view('sale.view', compact('sale'));
    }

    public function edit(sale $sale)
    {
        $products = products::orderby('name', 'asc')->get();
        $customers = accounts::active()->customer()->get();
        $accounts = accounts::active()->business()->get();
        $attendants = attendants::active()->get();

        return view('sale.edit', compact('products', 'customers', 'accounts', 'sale', 'attendants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sale $sale)
    {
        try {
            if ($request->isNotFilled('id')) {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            foreach ($sale->details as $product) {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }

            foreach ($sale->payments as $payment) {
                $payment->delete();
            }
            transactions::where('refID', $sale->refID)->delete();

            $sale->update(
                [
                    'customer_id' => $request->customer_id,
                    'attendant_id' => $request->attendant,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'status' => $request->status,
                ]
            );

            $ids = $request->id;
            $ref = $sale->refID;

            $total = 0;
            foreach ($ids as $key => $id) {
                if ($request->qty[$key] > 0) {
                    $qty = $request->qty[$key];
                    $price = $request->price[$key];
                    $amount = $price * $qty;
                    $total += $amount;

                    sale_details::create(
                        [
                            'sale_id' => $sale->id,
                            'product_id' => $id,
                            'price' => $price,
                            'qty' => $qty,
                            'amount' => $amount,
                            'date' => $request->date,
                            'refID' => $ref,
                        ]
                    );
                    createStock($id, 0, $qty, $request->date, "Sold in $sale->id", $ref);
                }
            }

            $sale->update(
                [
                    'total' => $total,
                ]
            );

            if ($request->status == 'paid') {
                $account_ids = $request->account_id;
                $payment_amount = $request->payment_amount;
                $payment_notes = $request->payment_notes;
                foreach ($account_ids as $key => $account_id) {
                    if ($payment_amount[$key] > 0) {
                        $account = accounts::find($account_id);
                        createTransaction($account->id, $request->date, $payment_amount[$key], 0, "Payment of Sale # $sale->id Remarks".$payment_notes[$key], $ref);

                        salePayments::create(
                            [
                                'sale_id' => $sale->id,
                                'account_id' => $account_id,
                                'amount' => $payment_amount[$key],
                                'notes' => $payment_notes[$key],
                                'date' => $request->date,
                                'refID' => $ref,
                            ]
                        );
                    }

                }
            } else {
                createTransaction($request->customer_id, $request->date, $total, 0, "Pending Amount of Sale # $sale->id", $ref);
            }
            DB::commit();
            session()->forget('confirmed_password');

            return to_route('sale.index')->with('success', 'Sale Updated');
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
            $sale = sale::find($id);

            foreach ($sale->details as $product) {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            foreach ($sale->payments as $payment) {
                $payment->delete();
            }
            transactions::where('refID', $sale->refID)->delete();
            $sale->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('sale.index')->with('success', 'Sale Deleted');
        } catch (Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('sale.index')->with('error', $e->getMessage());
        }
    }

    public function getSignleProduct($id)
    {
        $product = products::find($id);

        return $product;
    }
}
