<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transfers extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function from()
    {
        return $this->belongsTo(accounts::class, 'from_id');
    }

    public function to()
    {
        return $this->belongsTo(accounts::class, 'to_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
