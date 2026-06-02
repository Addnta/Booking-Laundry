<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'booking_id' => 1,
                'order_id' => 'ORDER-001',
                'snap_token' => null,
                'payment_type' => 'bank_transfer',
                'transaction_status' => 'paid',
                'gross_amount' => '180000.00',
                'paid_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('payments')->insertBatch($data);
    }
}
