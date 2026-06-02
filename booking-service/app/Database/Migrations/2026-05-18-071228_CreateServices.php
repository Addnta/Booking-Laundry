<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServices extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_service' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_service' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'harga' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id_service', true);
        $this->forge->createTable('services');
    }

    public function down()
    {
        $this->forge->dropTable('services', true);
    }
}
