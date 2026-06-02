<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'booking_code' => 'BOOK-001',
                'user_id' => 2,
                'service_id' => 2,
                'schedule_id' => 2,
                'booking_status' => 'confirmed',
                'payment_status' => 'paid',
                'notes' => 'Booking untuk perawatan wajah demo',
                'total_price' => '180000.00',
            ],
            [
                'id' => 2,
                'booking_code' => 'BOOK-002',
                'user_id' => 1,
                'service_id' => 1,
                'schedule_id' => 1,
                'booking_status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => 'Booking untuk potong rambut administratif',
                'total_price' => '120000.00',
            ],
        ];

        $this->db->table('bookings')->insertBatch($data);
    }
}
