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

        $results = $this->request('destination/province');
        if (!empty($results)) {
            $this->cacheResult('provinces', $results, 86400); // cache 24 hours
        }

        return $results;
    }

    public function getCities(int $provinceId): array
    {
        $cacheKey = 'cities_' . $provinceId;
        $cache = $this->getCachedResult($cacheKey);
        if (!empty($cache)) {
            return $cache;
        }

        $results = $this->request('destination/city/' . $provinceId);
        if (!empty($results)) {
            $this->cacheResult($cacheKey, $results, 86400);
        }

        return $results;
    }

    public function calculateCost(int $destinationCityId, float $weight): array
    {
        $payload = [
            'origin' => $this->config->originCityId,
            'destination' => $destinationCityId,
            'weight' => max(1, ceil($weight * 1000)),
            'courier' => 'jne',
            'price' => 'lowest',
        ];

        $cacheKey = 'cost_' . sha1(json_encode($payload));
        $cache = $this->getCachedResult($cacheKey);
        if (!empty($cache)) {
            return $cache;
        }

        $result = $this->request('calculate/domestic-cost', [], 'POST', $payload);
        if (!empty($result)) {
            $this->cacheResult($cacheKey, $result, 3600);
        }

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
            'Key: ' . $this->config->apiKey,
        ]);
        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
                'Key: ' . $this->config->apiKey,
            ]);
        }
        // Set timeouts to avoid long blocking when RajaOngkir is slow/unreachable
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); // 5 seconds to connect
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 10 seconds total

        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payload));
        }

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false || $errno !== 0) {
            log_message('error', 'RajaOngkir request failed for {endpoint}: {error}', [
                'endpoint' => $endpoint,
                'error' => $error ?: 'unknown error',
            ]);
            return [];
        }

        $data = json_decode($response, true);

        if ($status < 200 || $status >= 300) {
            log_message('error', 'RajaOngkir returned HTTP {status} for {endpoint}: {response}', [
                'status' => (string) $status,
                'endpoint' => $endpoint,
                'response' => substr((string) $response, 0, 500),
            ]);
            return [];
        }

        if (!is_array($data) || !isset($data['data'])) {
            log_message('error', 'RajaOngkir response format invalid for {endpoint}', [
                'endpoint' => $endpoint,
            ]);
            return [];
        }

        return $data['data'] ?? [];
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
