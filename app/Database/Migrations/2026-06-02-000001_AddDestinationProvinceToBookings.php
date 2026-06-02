<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDestinationProvinceToBookings extends Migration
{
    public function up()
    {
        $fields = [
            'destination_province_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'delivery_type',
            ],
        ];

        $this->forge->addColumn('bookings', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', ['destination_province_id']);
    }
}
