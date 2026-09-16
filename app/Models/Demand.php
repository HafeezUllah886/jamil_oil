<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(accounts::class, 'customer_id');
    }

    public function details()
    {
        return $this->hasMany(DemandDetail::class, 'demand_id');
    }

    public function deliveries()
    {
        return $this->hasMany(DemandDelivery::class, 'demand_id');
    }
}
