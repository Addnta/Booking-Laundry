<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class WhatsApp extends BaseConfig
{
    /**
     * PetaPod API Base URL
     */
    public string $baseUrl = 'https://servicelaundry-05a3.sg-2.podo.top/dashboard';

    /**
     * PetaPod API Authentication Key
     * Store this in .env for production: WHATSAPP_API_KEY
     */
    public string $apiKey = '6ab9f50e73e50ce363c5793b8f5baf67528b5384fff1cb29';

    /**
     * Business Phone Number (WhatsApp Business Account)
     * Store this in .env for production: WHATSAPP_BUSINESS_PHONE
     */
    public string $businessPhone = '';

    /**
     * Enable WhatsApp Integration
     * Store this in .env for production: WHATSAPP_ENABLED
     */
    public bool $enabled = true;

    /**
     * Constructor
     * Load configuration from environment variables if available
     */
    public function __construct()
    {
        parent::__construct();

        // Load from .env file
        if (function_exists('env')) {
            $this->apiKey = env('WHATSAPP_API_KEY', $this->apiKey);
            $this->baseUrl = env('WHATSAPP_BASE_URL', $this->baseUrl);
            $this->businessPhone = env('WHATSAPP_BUSINESS_PHONE', $this->businessPhone);
            $this->enabled = env('WHATSAPP_ENABLED', $this->enabled);
        }
    }
}
