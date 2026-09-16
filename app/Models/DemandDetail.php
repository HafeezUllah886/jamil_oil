<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandDetail extends Model
{
    protected $guarded = [];

    public function demand()
    {
        return $this->belongsTo(Demand::class, 'demand_id');
    }

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }
}
