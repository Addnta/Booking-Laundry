<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_staff' => 'Andi',
            ],
            [
                'nama_staff' => 'Siti',
            ],
            [
                'nama_staff' => 'Rian',
            ]
        ];

        $this->db->table('staff')->insertBatch($data);
    }
}
