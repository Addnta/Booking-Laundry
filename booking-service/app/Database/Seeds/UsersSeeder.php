<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Admin Laundry',
                'no_hp' => '081111111111',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
            ],
            [
                'nama' => 'Widia',
                'no_hp' => '082222222222',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'customer',
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}