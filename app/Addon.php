<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    /**
     * Set timestamps off
     */
    public $timestamps = false;

    /**
     * Get rentals with a certain addon
     */
    public function rentals()
    {
        return $this->belongsToMany(\App\Rental::class, 'rental_addons');
    }

    public function getAddonPrice($name)
    {
        $array = Arr::pluck($this->all()->toArray(), 'name');

        foreach ($array as $key => $value) {
            if ($value == $name) {
                $id = $key + 1;
            }
        }

        return $this->find($id)->price;
    }
}
