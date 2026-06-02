<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Database\Seeds\BookingsSeeder;
use App\Database\Seeds\PaymentsSeeder;
use App\Database\Seeds\SchedulesSeeder;
use App\Database\Seeds\ServicesSeeder;
use App\Database\Seeds\UserSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ServicesSeeder::class);
        $this->call(SchedulesSeeder::class);
        $this->call(BookingsSeeder::class);
        $this->call(PaymentsSeeder::class);
    }
}
