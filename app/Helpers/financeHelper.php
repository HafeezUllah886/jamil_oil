<?php

use App\Models\transactions;

function createTransaction($accountID, $date, $cr, $db, $notes, $ref)
{
    transactions::create(
        [
            'account_id' => $accountID,
            'date' => $date,
            'cr' => $cr,
            'db' => $db,
            'notes' => $notes,
            'refID' => $ref,
        ]
    );

}

function getAccountBalance($id)
{
    $transactions = transactions::where('account_id', $id);

    $cr = $transactions->sum('cr');
    $db = $transactions->sum('db');
    $balance = $cr - $db;

    return $balance;
}

function numberToWords($number)
{
    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);

    return ucfirst($f->format($number));
}

function spotBalanceBefore($id, $ref)
{
    $cr = transactions::where('accountID', $id)->where('refID', '<', $ref)->sum('cr');
    $db = transactions::where('accountID', $id)->where('refID', '<', $ref)->sum('db');

    return $balance = $cr - $db;
}

function spotBalance($id, $ref)
{
    $cr = transactions::where('accountID', $id)->where('refID', '<=', $ref)->sum('cr');
    $db = transactions::where('accountID', $id)->where('refID', '<=', $ref)->sum('db');

    return $balance = $cr - $db;
}
