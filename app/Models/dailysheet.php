<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dailysheet extends Model
{
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(dailysheet_detail::class);
    }
}

class dailysheet_detail extends Model
{
    protected $guarded = [];

    protected $table = 'dailysheets_details';
}
