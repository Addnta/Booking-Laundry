<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeliveryAndCalendarToBookingsTable extends Migration
{
    public function up()
    {
        $fields = [
            'delivery_type' => [
                'type' => 'ENUM',
                'constraint' => ['pickup', 'delivery'],
                'default' => 'pickup',
                'null' => false,
            ],
            'destination_city_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'destination_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'shipping_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => '0.00',
            ],
            'google_event_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];

        $this->forge->addColumn('bookings', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', [
            'delivery_type',
            'destination_city_id',
            'destination_address',
            'shipping_cost',
            'google_event_id',
        ]);
    }
}
