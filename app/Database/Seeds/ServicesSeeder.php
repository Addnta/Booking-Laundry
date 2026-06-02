<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Cuci Kiloan',
                'description' => 'Cuci kiloan biasa, harga murah dan cepat.',
                'price' => '7000.00',
                'duration' => 24,
                'photo' => null,
            ],
            [
                'id' => 2,
                'name' => 'Cuci Plus Gosok',
                'description' => 'Cuci + gosok untuk pakaian rapi dan siap pakai.',
                'price' => '8000.00',
                'duration' => 24,
                'photo' => null,
            ],
            [
                'id' => 3,
                'name' => 'Cuci Kilat',
                'description' => 'Cuci kilat untuk hasil lebih cepat.',
                'price' => '9000.00',
                'duration' => 12,
                'photo' => null,
            ],
        ];

        $this->db->table('services')->insertBatch($data);
    }
}
