<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingDetails extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('booking_details')) {
            return;
        }

        $usingNewSchema = $this->db->fieldExists('booking_code', 'bookings');
        $bookingPrimary = $usingNewSchema ? 'id' : 'id_booking';
        $servicePrimary = $usingNewSchema ? 'id' : 'id_service';
        $bookingType = $usingNewSchema ? 'BIGINT' : 'INT';
        $serviceType = $usingNewSchema ? 'BIGINT' : 'INT';

        $this->forge->addField([
            'id_detail' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'booking_id' => [
                'type' => $bookingType,
                'constraint' => 20,
                'unsigned' => true,
            ],
            'service_id' => [
                'type' => $serviceType,
                'constraint' => 20,
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
        ]);

        $this->forge->addKey('id_detail', true);

        $this->forge->addForeignKey('booking_id', 'bookings', $bookingPrimary);
        $this->forge->addForeignKey('service_id', 'services', $servicePrimary);

        $this->forge->createTable('booking_details');
    }

    public function down()
    {
        $this->forge->dropTable('booking_details', true);
    }
}
