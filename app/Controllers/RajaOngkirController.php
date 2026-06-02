<?php

namespace App\Controllers;

use App\Libraries\RajaOngkirService;

class RajaOngkirController extends BaseController
{
    public function provinces()
    {
        $service = new RajaOngkirService();

        return $this->response->setJSON($service->getProvinces());
    }

    public function cities($provinceId)
    {
        $service = new RajaOngkirService();

        return $this->response->setJSON($service->getCities((int) $provinceId));
    }

    public function cost()
    {
        $destinationCityId = (int) $this->request->getPost('destination_city_id');
        $weight = (float) $this->request->getPost('weight');

        if ($destinationCityId <= 0 || $weight <= 0) {
            $payload = json_decode($this->request->getBody(), true);
            $destinationCityId = (int) ($payload['destination_city_id'] ?? 0);
            $weight = (float) ($payload['weight'] ?? 0);
        }

        $service = new RajaOngkirService();
        $result = $service->calculateCost($destinationCityId, $weight);

        return $this->response->setJSON($result);
    }
}
