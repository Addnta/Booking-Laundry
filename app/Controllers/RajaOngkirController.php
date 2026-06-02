<?php

namespace App\Controllers;

use App\Libraries\RajaOngkirService;

class RajaOngkirController extends BaseController
{
    public function provinces()
    {
        $service = new RajaOngkirService();
        $results = $service->getProvinces();

        if (empty($results)) {
            return $this->response->setStatusCode(503)->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch provinces from RajaOngkir',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $results,
        ]);
    }

    public function cities($provinceId)
    {
        $service = new RajaOngkirService();
        $results = $service->getCities((int) $provinceId);

        if (empty($results)) {
            return $this->response->setStatusCode(503)->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch cities from RajaOngkir',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $results,
        ]);
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

        if (empty($result)) {
            return $this->response->setStatusCode(503)->setJSON([
                'status' => 'error',
                'message' => 'Failed to calculate shipping cost',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
