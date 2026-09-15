<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\transactions;
use App\Models\transfers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transfers = transfers::orderBy('id', 'desc')->get();
        $accounts = accounts::active()->get();

        return view('finance.transfer', compact('transfers', 'accounts'));
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
            transfers::create(
                [
                    'from_id' => $request->from_id,
                    'to_id' => $request->to_id,
                    'user_id' => auth()->user()->id,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            createTransaction($request->to_id, $request->date, $request->amount, 0, 'Amount Received <br>'.$request->notes, $ref);
            createTransaction($request->from_id, $request->date, 0, $request->amount, 'Amount Received <bt>'.$request->notes, $ref);

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

        return view('Finance.receiving.receipt', compact('receiving'));
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
            transfers::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('transfers.index')->with('success', 'Receiving Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('transfers.index')->with('error', $e->getMessage());
        }
    }
}
