<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWeightToBookings extends Migration
{
    public function up()
    {
        $fields = [
            'weight' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'default' => '0.00',
                'after' => 'schedule_id',
            ],
        ];

        $this->forge->addColumn('bookings', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', ['weight']);
    }
}
