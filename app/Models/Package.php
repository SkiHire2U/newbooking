<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    //

    public function getPackageName($id)
    {
        return $this->find($id)->name;
    }

    public function getPackageLevel($id)
    {
        return $this->find($id)->level;
    }

    public function getPackageType($id)
    {
        return $this->find($id)->type;
    }

    public function getPackagePrice($id, $days, $boots = 'off')
    {
        $package = $this->find($id);

        $prices = json_decode($package->prices);

        if ($boots == 'on') {
            $price = $prices->$days->boots;
        } else {
            $price = $prices->$days->flat;
        }

        return $price;
    }
}
