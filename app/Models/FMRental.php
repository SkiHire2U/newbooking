<?php

namespace App\Models;

use GearboxSolutions\EloquentFileMaker\Database\Eloquent\FMModel;

class FMRental extends FMModel
{
    //

    protected $layout = 'rentals';
    protected $connection = 'filemaker';

    protected $fieldMapping = [
        'Number of People' => 'party_number'
    ];

}
