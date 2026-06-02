<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
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

            'booking_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],

            'user_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],

            'service_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],

            'schedule_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],

            'booking_status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'pending',
                    'confirmed',
                    'rejected',
                    'process',
                    'completed',
                    'cancelled'
                ],
                'default' => 'pending',
            ],

            'payment_status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'unpaid',
                    'paid',
                    'failed'
                ],
                'default' => 'unpaid',
            ],

            'weight' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'default'    => '0.00',
            ],

            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

        ]);

        $this->forge->addKey('id', true);

        // foreign key
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'service_id',
            'services',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'schedule_id',
            'schedules',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings');
    }
}
