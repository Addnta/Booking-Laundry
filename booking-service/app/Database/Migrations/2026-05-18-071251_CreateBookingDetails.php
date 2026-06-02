<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingDetails extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detail' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'subtotal' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id_detail', true);

        $this->forge->addForeignKey('booking_id', 'bookings', 'id_booking');
        $this->forge->addForeignKey('service_id', 'services', 'id_service');

        $this->forge->createTable('booking_details');
    }

    public function down()
    {
        $this->forge->dropTable('booking_details', true);
    }
}
