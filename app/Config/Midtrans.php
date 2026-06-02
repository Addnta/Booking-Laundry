<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public string $serverKey;
    public string $clientKey;
    public string $apiUrl = 'https://api.sandbox.midtrans.com/v2/transactions';

    public function __construct()
    {
        parent::__construct();

        $this->serverKey = env('midtrans.serverKey', '');
        $this->clientKey = env('midtrans.clientKey', '');
    }
}
