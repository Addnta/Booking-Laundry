<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'booking_id' => 1,
                'jumlah_bayar' => 14000,
                'tanggal_bayar' => '2026-05-18',
                'metode' => 'Cash',
                'status' => 'Lunas',
            ],
            [
                'booking_id' => 2,
                'jumlah_bayar' => 10000,
                'tanggal_bayar' => '2026-05-19',
                'metode' => 'Transfer',
                'status' => 'Pending',
            ]
        ];

        $this->db->table('payments')->insertBatch($data);
    }
}
