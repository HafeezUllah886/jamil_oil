<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\issuePayment;
use App\Models\paymentReceiving;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IssuePaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = issuePayment::orderBy('id', 'desc')->get();
        $tos = accounts::where('type', '!=', 'Business')->get();
        $accounts = accounts::Business()->get();

        return view('finance.issue', compact('payments', 'tos', 'accounts'));
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
            issuePayment::create(
                [
                    'to_id' => $request->to_id,
                    'account_id' => $request->account_id,
                    'user_id' => auth()->user()->id,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            createTransaction($request->account_id, $request->date, 0, $request->amount, 'Amount Issued <br>'.$request->notes, $ref);
            createTransaction($request->to_id, $request->date, $request->amount, 0, 'Amount Issued <bt>'.$request->notes, $ref);

            DB::commit();

            return back()->with('success', 'Payment Issued');
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
        $receiving = issuePayment::find($id);

        return view('Finance.receiving.receipt', compact('receiving'));
    }

    public function edit(issuePayment $paymentReceiving)
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
            issuePayment::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('issue.index')->with('success', 'Payment Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('issue.index')->with('error', $e->getMessage());
        }
    }
}
