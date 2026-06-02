<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_service' => 'Cuci Kering',
                'harga' => 7000,
            ],
            [
                'nama_service' => 'Cuci Setrika',
                'harga' => 10000,
            ],
            [
                'nama_service' => 'Laundry Express',
                'harga' => 15000,
            ],
            [
                'nama_service' => 'Cuci Karpet',
                'harga' => 25000,
            ]
        ];

        $this->db->table('services')->insertBatch($data);
    }
}
