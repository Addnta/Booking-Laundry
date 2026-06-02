<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'booking_id',
        'order_id',
        'snap_token',
        'payment_type',
        'transaction_status',
        'gross_amount',
        'paid_at',
        'created_at'
    ];
}