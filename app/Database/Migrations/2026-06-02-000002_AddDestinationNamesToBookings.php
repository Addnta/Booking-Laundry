<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDestinationNamesToBookings extends Migration
{
    public function up()
    {
        $fields = [
            'destination_province_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'destination_province_id',
            ],
            'destination_city_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'destination_city_id',
            ],
        ];

        $this->forge->addColumn('bookings', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', [
            'destination_province_name',
            'destination_city_name',
        ]);
    }
}
