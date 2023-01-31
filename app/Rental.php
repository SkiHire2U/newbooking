<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
	public function booking() {
        return $this->belongsTo('App\Booking');
    }

    public function addons() {
		return $this->belongsToMany('App\Addon', 'rental_addons');
	}
}
