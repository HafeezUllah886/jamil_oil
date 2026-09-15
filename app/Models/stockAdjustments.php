<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stockAdjustments extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
