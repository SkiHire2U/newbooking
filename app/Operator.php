<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    public function accommodations()
    {
        return $this->hasMany('App\Accommodation');
    }
}
