<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function rentals()
    {
        return $this->hasMany('App\Rental');
    }
}
