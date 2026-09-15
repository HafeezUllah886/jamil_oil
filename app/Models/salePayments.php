<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class salePayments extends Model
{
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class);
    }

    public function sale()
    {
        return $this->belongsTo(sale::class);
    }
}
