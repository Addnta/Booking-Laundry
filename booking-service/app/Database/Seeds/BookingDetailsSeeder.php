<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingDetailsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'booking_id' => 1,
                'service_id' => 1,
                'quantity' => 2,
                'subtotal' => 14000,
            ],
            [
                'booking_id' => 2,
                'service_id' => 2,
                'quantity' => 1,
                'subtotal' => 10000,
            ]
        ];

        $this->db->table('booking_details')->insertBatch($data);
    }
}
