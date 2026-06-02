<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table = 'schedules';

    protected $primaryKey = 'id';

    protected $allowedFields = [

        'service_id',

        'date',

        'time_slot',

        'capacity'
    ];

    protected $useTimestamps = false;

    protected $returnType = 'array';
}