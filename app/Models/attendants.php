<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendants extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive()
    {
        return $this->where('is_active', true);
    }
}
