<?php

namespace App\Models;

use GearboxSolutions\EloquentFileMaker\Database\Eloquent\FMModel;

class FMRental extends FMModel
{
    //

    protected $layout = 'rentals';
    protected $connection = 'filemaker';

    protected $fieldMapping = [
        'Number of People' => 'party_number',
        'Delivery Address' => 'delivery_address',
        'Project Name' => 'project_name',
    ];

    protected $casts = [
        'Date' => 'date',
        'DateEnd' => 'date',
    ];

}
