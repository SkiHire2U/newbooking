<?php

namespace App\Models;

use GearboxSolutions\EloquentFileMaker\Database\Eloquent\FMModel;

class FMClient extends FMModel
{
    //

    protected $layout = 'clients';
    protected $connection = 'filemaker';

    protected $fieldMapping = [
        'Mobile Phone' => 'phone',
        'Address 2' => 'address2'
    ];

}
