<?php

namespace App\Libraries;

use Config\RajaOngkir;

class RajaOngkirService
{
    protected RajaOngkir $config;

    public function __construct()
    {
        $this->config = config('RajaOngkir');
    }

    public function getProvinces(): array
    {
        $cache = $this->getCachedResult('provinces');
        if (!empty($cache)) {
            return $cache;
        }

        $results = $this->request('province');
        if (!empty($results)) {
            $this->cacheResult('provinces', $results, 86400); // cache 24 hours
        }

        return $results;
    }

    public function getCities(int $provinceId): array
    {
        return $this->request('city', ['province' => $provinceId]);
    }

    public function calculateCost(int $destinationCityId, float $weight): array
    {
        $payload = [
            'origin' => $this->config->originCityId,
            'originType' => 'city',
            'destination' => $destinationCityId,
            'destinationType' => 'city',
            'weight' => max(1, ceil($weight * 1000)),
            'courier' => 'jne',
        ];

        $result = $this->request('cost', [], 'POST', $payload);

        return $result;
    }

    protected function request(string $endpoint, array $query = [], string $method = 'GET', array $payload = []): array
    {
        if (empty($this->config->apiKey)) {
            return [];
        }

        $url = $this->config->apiUrl . '/' . $endpoint;

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'key: ' . $this->config->apiKey,
        ]);
        // Set timeouts to avoid long blocking when RajaOngkir is slow/unreachable
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); // 5 seconds to connect
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 10 seconds total

        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false || $errno !== 0) {
            return [];
        }

        $data = json_decode($response, true);

        return $data['rajaongkir']['results'] ?? [];
    }

    protected function getCachePath(string $key): string
    {
        $writePath = defined('WRITEPATH') ? rtrim(WRITEPATH, '\\/').DIRECTORY_SEPARATOR : realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR;
        return $writePath . 'cache' . DIRECTORY_SEPARATOR . 'rajaongkir_' . $key . '.json';
    }

    protected function cacheResult(string $key, array $data, int $ttl = 3600): void
    {
        $path = $this->getCachePath($key);
        $payload = [
            'ts' => time(),
            'ttl' => $ttl,
            'data' => $data,
        ];
        @file_put_contents($path, json_encode($payload));
    }

    protected function getCachedResult(string $key): array
    {
        $path = $this->getCachePath($key);
        if (!is_file($path)) {
            return [];
        }

        $json = @file_get_contents($path);
        if ($json === false) {
            return [];
        }

        $payload = json_decode($json, true);
        if (empty($payload) || !isset($payload['ts']) || !isset($payload['ttl']) || !isset($payload['data'])) {
            return [];
        }

        if ($payload['ts'] + (int) $payload['ttl'] < time()) {
            @unlink($path);
            return [];
        }

        return $payload['data'];
    }
}
