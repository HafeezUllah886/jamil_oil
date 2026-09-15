<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accountAdjustments extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id');
    }

    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
