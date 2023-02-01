<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    public function accommodations()
    {
        return $this->hasMany(\App\Models\Accommodation::class);
    }
}
