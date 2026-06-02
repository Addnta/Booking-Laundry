<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePayments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_payment' => [
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
            'jumlah_bayar' => [
                'type' => 'INT',
                'constraint' => 11,
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

        $this->forge->addForeignKey('booking_id', 'bookings', 'id_booking');

        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments', true);
    }
}
