<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'tanggal' => '2026-05-18',
                'user_id' => 1,
                'staff_id' => 1,
            ],
            [
                'tanggal' => '2026-05-19',
                'user_id' => 2,
                'staff_id' => 2,
            ]
        ];

        $this->db->table('bookings')->insertBatch($data);
    }
}
