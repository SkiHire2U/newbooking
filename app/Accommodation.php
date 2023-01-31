<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    public function operator()
    {
        return $this->belongsTo('App\Operator');
    }

    public function getAccommodationName($id)
    {
        return $this->find($id)->name;
    }

    public function getAccommodationDiscount($id)
    {
        return $this->find($id)->discount;
    }
}
