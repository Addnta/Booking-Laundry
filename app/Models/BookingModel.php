<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'bookings';

    protected $primaryKey = 'id';

    protected $allowedFields = [

        'booking_code',

        'user_id',

        'service_id',

        'schedule_id',
        'assigned_staff_id',

        'booking_status',

        'delivery_type',

        'destination_province_id',
        'destination_province_name',

        'weight',

        'destination_city_id',
        'destination_city_name',

        'destination_address',

        'shipping_cost',

        'payment_status',

        'notes',

        'total_price',

        'google_event_id'
        ,
        'work_proof_photo'
    ];

    protected $useTimestamps = true;

    protected $returnType = 'array';
}
