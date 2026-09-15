<?php

use App\Models\accounts;
use App\Models\purchase;
use App\Models\sale_details;
use Illuminate\Support\Facades\DB;

function totalSales()
{
    return $sales = sale_details::sum('amount');
}

function totalPurchases()
{
    return purchase::sum('total');
}

function myBalance()
{
    $accounts = accounts::where('type', 'Business')->get();
    $balance = 0;
    foreach ($accounts as $account) {
        $balance += getAccountBalance($account->id);
    }

    return $balance;
}

function customerBalance()
{
    $accounts = accounts::where('type', 'Customer')->get();
    $balance = 0;
    foreach ($accounts as $account) {
        $balance += getAccountBalance($account->id);
    }

    return $balance;
}

function dashboard()
{
    $domains = config('app.domains');
    $current_domain = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
    if (! in_array($current_domain, $domains)) {
        exit('Invalid Configrations');
    }

    $files = config('app.files');
    $file2 = filesize(public_path('assets/images/Header.png'));

    if ($files[1] != $file2) {
        abort(500, 'Something Went Wrong!');
    }

    $databases = config('app.databases');
    $current_db = DB::connection()->getDatabaseName();
    if (! in_array($current_db, $databases)) {
        abort(500, 'Connection Failed!');
    }
}

function supplierBalance()
{
    $accounts = accounts::where('type', 'Supplier')->get();
    $balance = 0;
    foreach ($accounts as $account) {
        $balance += getAccountBalance($account->id);
    }

    return $balance;
}
