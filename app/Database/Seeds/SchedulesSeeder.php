<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SchedulesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'service_id' => 1,
                'date' => date('Y-m-d', strtotime('+1 day')),
                'time_slot' => '10:00:00',
                'capacity' => 3,
            ],
            [
                'id' => 2,
                'service_id' => 2,
                'date' => date('Y-m-d', strtotime('+2 days')),
                'time_slot' => '13:00:00',
                'capacity' => 2,
            ],
            [
                'id' => 3,
                'service_id' => 3,
                'date' => date('Y-m-d', strtotime('+3 days')),
                'time_slot' => '15:00:00',
                'capacity' => 4,
            ],
        ];

        $this->db->table('schedules')->insertBatch($data);
    }
}
