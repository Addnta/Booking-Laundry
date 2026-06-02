<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class GoogleCalendar extends BaseConfig
{
    public string $serviceAccountJsonPath;
    public string $calendarId;
    public string $tokenUrl = 'https://oauth2.googleapis.com/token';
    public string $apiUrl = 'https://www.googleapis.com/calendar/v3';

    public function __construct()
    {
        parent::__construct();

        $this->serviceAccountJsonPath = env('google_calendar.serviceAccountJsonPath', '');
        $this->calendarId = env('google_calendar.calendarId', 'primary');
    }
}
