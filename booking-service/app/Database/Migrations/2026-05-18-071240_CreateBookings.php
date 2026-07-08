<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookings extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('bookings')) {
            return;
        }

        $this->forge->addField([
            'id_booking' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'staff_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey('id_booking', true);

        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('staff_id', 'staff', 'id_staff');

        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings', true);
    }
}
