<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public string $serverKey;
    public string $clientKey;

    // Endpoint Snap Sandbox
    public string $apiUrl = 'https://app.sandbox.midtrans.com/snap/v1/transactions';

    public function __construct()
    {
        parent::__construct();

        $this->serverKey = env('midtrans.serverKey', '');
        $this->clientKey = env('midtrans.clientKey', '');
    }
}