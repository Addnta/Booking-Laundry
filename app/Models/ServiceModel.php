<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table = 'services';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'price',
        'duration',
        'photo'
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $returnType = 'array';
}