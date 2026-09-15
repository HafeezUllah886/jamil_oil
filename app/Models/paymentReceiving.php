<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentReceiving extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function from()
    {
        return $this->belongsTo(accounts::class, 'from_id');
    }

    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
