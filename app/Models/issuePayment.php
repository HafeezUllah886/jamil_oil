<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class issuePayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function to()
    {
        return $this->belongsTo(accounts::class, 'to_id');
    }

    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
