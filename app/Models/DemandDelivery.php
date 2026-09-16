<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandDelivery extends Model
{
    protected $guarded = [];

    public function demand()
    {
        return $this->belongsTo(Demand::class, 'demand_id');
    }

    public function details()
    {
        return $this->hasMany(DemandDeliveryDetail::class, 'delivery_id');
    }
}
