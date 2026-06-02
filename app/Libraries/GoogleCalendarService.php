<?php

namespace App\Libraries;

use Config\GoogleCalendar;

class GoogleCalendarService
{
    protected GoogleCalendar $config;
    protected array $credentials;

    public function __construct()
    {
        $this->config = config('GoogleCalendar');
        $this->credentials = $this->loadCredentials();
    }

    public function isConfigured(): bool
    {
        return !empty($this->credentials) && !empty($this->config->calendarId);
    }

    public function createEvent(array $booking): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $accessToken = $this->getAccessToken();
        if (empty($accessToken)) {
            return null;
        }

        $event = $this->buildEvent($booking);
        $url = sprintf('%s/calendars/%s/events', rtrim($this->config->apiUrl, '/'), rawurlencode($this->config->calendarId));

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($event));

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($status !== 200 && $status !== 201) {
            return null;
        }

        $data = json_decode($response, true);

        return $data['id'] ?? null;
    }

    protected function loadCredentials(): array
    {
        $path = $this->resolveServiceAccountPath($this->config->serviceAccountJsonPath);

        if (empty($path) || !is_file($path)) {
            return [];
        }

        $json = file_get_contents($path);
        if ($json === false) {
            return [];
        }

        return json_decode($json, true) ?: [];
    }

    protected function resolveServiceAccountPath(string $path): string
    {
        if (empty($path)) {
            return '';
        }

        if (is_file($path)) {
            return $path;
        }

        $rootPath = defined('ROOTPATH') ? rtrim(ROOTPATH, '\\/') . DIRECTORY_SEPARATOR : realpath(__DIR__ . '/../../');
        $writePath = defined('WRITEPATH') ? rtrim(WRITEPATH, '\\/') . DIRECTORY_SEPARATOR : $rootPath . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR;

        $candidates = [
            $rootPath . DIRECTORY_SEPARATOR . $path,
            $writePath . basename($path),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return '';
    }

    protected function getAccessToken(): ?string
    {
        if (empty($this->credentials['private_key']) || empty($this->credentials['client_email'])) {
            return null;
        }

        $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $now = time();
        $claims = [
            'iss' => $this->credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/calendar',
            'aud' => $this->config->tokenUrl,
            'exp' => $now + 3600,
            'iat' => $now,
        ];
        $payload = $this->base64UrlEncode(json_encode($claims));
        $signatureBase = sprintf('%s.%s', $header, $payload);

        openssl_sign($signatureBase, $signature, $this->credentials['private_key'], 'SHA256');
        $jwt = sprintf('%s.%s.%s', $header, $payload, $this->base64UrlEncode($signature));

        $curl = curl_init($this->config->tokenUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);

        return $data['access_token'] ?? null;
    }

    protected function buildEvent(array $booking): array
    {
        $serviceName = $booking['service_name'] ?? 'Laundry Service';
        $bookingCode = $booking['booking_code'] ?? 'BOOKING';
        $date = $booking['date'] ?? date('Y-m-d');
        $timeSlot = $booking['time_slot'] ?? '09:00-11:00';

        $times = explode('-', $timeSlot);
        $startTime = trim($times[0]) ?: '09:00';
        $endTime = trim($times[1]) ?: date('H:i', strtotime($startTime . ' +2 hours'));

        $startDateTime = sprintf('%sT%s:00', $date, $startTime);
        $endDateTime = sprintf('%sT%s:00', $date, $endTime);

        $location = $booking['destination_address'] ?? 'Laundry outlet';

        return [
            'summary' => sprintf('Laundry Booking: %s', $bookingCode),
            'description' => sprintf("Booking %s untuk layanan %s. Status: %s", $bookingCode, $serviceName, $booking['booking_status'] ?? ''),
            'location' => $location,
            'start' => [
                'dateTime' => $startDateTime,
                'timeZone' => 'Asia/Jakarta',
            ],
            'end' => [
                'dateTime' => $endDateTime,
                'timeZone' => 'Asia/Jakarta',
            ],
            'attendees' => [],
        ];
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
