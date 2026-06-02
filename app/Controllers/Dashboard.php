<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function admin()
    {
        return view('dashboard/admin');
    }

    public function staff()
    {
        return view('dashboard/staff');
    }

    public function customer()
    {
        return view('dashboard/customer');
    }
}