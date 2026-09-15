<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transfer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function fromAccount()
    {
        return $this->belongsTo(accounts::class, 'from');
    }
    public function toAccount()
    {
        return $this->belongsTo(accounts::class, 'to');
    }
}
