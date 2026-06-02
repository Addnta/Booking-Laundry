<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_id',
        'rating',
        'review',
        'created_at',
    ];
    public $useTimestamps = false;
    protected $returnType = 'array';
}
