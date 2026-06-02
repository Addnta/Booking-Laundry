<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class Home extends BaseController
{
    public function index()
    {
        $serviceModel = new ServiceModel();

        $data['services'] =
            $serviceModel->findAll();

        return view('home', $data);
    }
}