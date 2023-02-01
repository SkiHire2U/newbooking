<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    public function getAddonMeta($key, $type)
    {
        $meta = $this->where('key', $key)->first();

        $meta->value = json_decode($meta->value);

        $return = 0;

        if ($type == 'Child') {
            if ($key == 'insurance_increments') {
                $return = $meta->value[0];
            } else {
                $return = 0;
            }
        } else {
            $return = $meta->value[1];
        }

        return $return;
    }
}
