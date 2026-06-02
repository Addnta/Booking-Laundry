<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'Administrator',

                'email' => 'admin@gmail.com',

                'password' => password_hash(
                    '123',
                    PASSWORD_DEFAULT
                ),

                'role' => 'admin',

                'phone' => '081111111111',

                'status' => 'active'
            ],

            [
                'name' => 'Staff Demo',

                'email' => 'staff@gmail.com',

                'password' => password_hash(
                    '123',
                    PASSWORD_DEFAULT
                ),

                'role' => 'staff',

                'phone' => '083333333333',

                'status' => 'active'
            ],

            [
                'name' => 'Customer Demo',

                'email' => 'customer@gmail.com',

                'password' => password_hash(
                    '123',
                    PASSWORD_DEFAULT
                ),

                'role' => 'customer',

                'phone' => '082222222222',

                'status' => 'active'
            ]

        ];

        $this->db->table('users')->insertBatch($data);
    }
}