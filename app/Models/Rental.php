<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    public function booking()
    {
        return $this->belongsTo(\App\Models\Booking::class);
    }

    public function addons()
    {
        return $this->belongsToMany(\App\Models\Addon::class, 'rental_addons');
    }
}
