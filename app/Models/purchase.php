<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(accounts::class, 'supplier_id');
    }

    public function details()
    {
        return $this->hasMany(purchase_details::class, 'purchase_id');
    }
}
