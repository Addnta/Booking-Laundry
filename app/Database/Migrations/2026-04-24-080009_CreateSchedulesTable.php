<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'service_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],

            'date' => [
                'type' => 'DATE',
            ],

            'time_slot' => [
                'type' => 'TIME',
            ],

            'capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

        ]);

        $this->forge->addKey('id', true);

        // foreign key
        $this->forge->addForeignKey(
            'service_id',
            'services',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('schedules');
    }

    public function down()
    {
        $this->forge->dropTable('schedules');
    }
}
