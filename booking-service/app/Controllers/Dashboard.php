<?php

namespace App\Controllers;

use App\Models\ServiceModel;
class Dashboard extends BaseController
{
    public function admin()
    {
        $model = new ServiceModel();

    $data['services'] = $model->findAll();

    return view('dashboard/admin', $data);
    }

    public function staff()
    {
        return view('dashboard/staff');
    }

    public function customer()
    {
         $serviceModel = new ServiceModel();

    $data['services'] = $serviceModel->findAll();

    return view('dashboard/customer', $data);
    }
}