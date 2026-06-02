<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class RajaOngkir extends BaseConfig
{
    public string $apiKey;
    public string $originCityId;
    public string $apiUrl = 'https://rajaongkir.komerce.id/api/v1';

    public function __construct()
    {
        parent::__construct();

        $this->apiKey = env('rajaongkir.apiKey', '');
        $this->originCityId = env('rajaongkir.originCityId', '');
    }
}
