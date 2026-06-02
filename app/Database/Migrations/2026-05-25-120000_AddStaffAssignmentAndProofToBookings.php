<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStaffAssignmentAndProofToBookings extends Migration
{
    public function up()
    {
        $fields = [
            'assigned_staff_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
                'after' => 'schedule_id',
            ],
            'work_proof_photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'google_event_id',
            ],
        ];

        $this->forge->addColumn('bookings', $fields);
        $this->db->query('ALTER TABLE bookings ADD CONSTRAINT fk_bookings_assigned_staff FOREIGN KEY (assigned_staff_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', ['assigned_staff_id', 'work_proof_photo']);
    }
}
