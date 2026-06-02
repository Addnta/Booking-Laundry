<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ServiceModel;

class ServicesApiController extends BaseController
{
    public function index()
    {
        $serviceModel = new ServiceModel();

        $services = $serviceModel->findAll();

        return $this->response->setJSON($services);
    }
}