<?php

namespace App\Http\Controllers;

use App\Models\accountAdjustments;
use App\Models\accounts;
use App\Models\paymentReceiving;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adjustments = accountAdjustments::orderBy('id', 'desc')->get();
        $accounts = accounts::active()->get();

        return view('finance.adjustments', compact('adjustments', 'accounts'));
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
        try {
            DB::beginTransaction();
            $ref = getRef();
            accountAdjustments::create(
                [
                    'account_id' => $request->account_id,
                    'user_id' => auth()->user()->id,
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            if ($request->type == 'Credit') {
                createTransaction($request->account_id, $request->date, $request->amount, 0, 'Account Adjusted  <br>'.$request->notes, $ref);
            } else {
                createTransaction($request->account_id, $request->date, 0, $request->amount, 'Account Adjusted  <br>'.$request->notes, $ref);
            }

            DB::commit();

            return back()->with('success', 'Receipt Saved');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $receiving = paymentReceiving::find($id);

        return view('finance.receiving.receipt', compact('receiving'));
    }

    public function edit(paymentReceiving $paymentReceiving)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, paymentReceiving $paymentReceiving)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ref)
    {

        try {
            DB::beginTransaction();
            accountAdjustments::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('adjustments.index')->with('success', 'Receiving Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('adjustments.index')->with('error', $e->getMessage());
        }
    }
}
