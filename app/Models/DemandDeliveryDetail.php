<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandDeliveryDetail extends Model
{
    protected $guarded = [];

    public function delivery()
    {
        return $this->belongsTo(DemandDelivery::class, 'delivery_id');
    }

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }
}
