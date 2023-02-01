<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    public function operator()
    {
        return $this->belongsTo(\App\Models\Operator::class);
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
