<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePayments extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('payments')) {
            return;
        }

        $bookingPrimary = $this->db->fieldExists('booking_code', 'bookings')
            ? 'id'
            : 'id_booking';
        $bookingType = $this->db->fieldExists('booking_code', 'bookings')
            ? 'BIGINT'
            : 'INT';

        $this->forge->addField([
            'id_payment' => [
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
            'jumlah_bayar' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'tanggal_bayar' => [
                'type' => 'DATE',
            ],
            'metode' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);

        $this->forge->addKey('id_payment', true);

        $this->forge->addForeignKey('booking_id', 'bookings', $bookingPrimary);

        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments', true);
    }
}
