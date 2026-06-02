<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaff extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_staff' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_staff' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);

        $this->forge->addKey('id_staff', true);
        $this->forge->createTable('staff');
    }

    public function down()
    {
        $this->forge->dropTable('staff', true);
    }
}