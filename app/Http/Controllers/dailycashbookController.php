<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\transactions;
use Illuminate\Http\Request;

class dailycashbookController extends Controller
{
    public function index()
    {
        return view('reports.dailycashbook.index');
    }

    public function details(Request $request)
    {
        $date = $request->date;
        $ids = accounts::business()->pluck('id')->toArray();

        $trans = transactions::with('account')->whereIn('account_id', $ids);

        $cr = (clone $trans)->whereDate('date', '<', $date)->sum('cr');
        $db = (clone $trans)->whereDate('date', '<', $date)->sum('db');
        $pre_balance = $cr - $db;

        $cr_trans = (clone $trans)->whereDate('date', $date)->where('cr', '>', 0)->get();
        $db_trans = (clone $trans)->whereDate('date', $date)->where('db', '>', 0)->get();

        return view('reports.dailycashbook.details', compact('date', 'pre_balance', 'cr_trans', 'db_trans'));
    }
}
