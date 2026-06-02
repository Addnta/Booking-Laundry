<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class DocsController extends BaseController
{
    public function index()
    {
        $endpoints = [
            [
                'method' => 'GET',
                'path' => '/api/services',
                'description' => 'List layanan publik',
            ],
            [
                'method' => 'GET',
                'path' => '/api/services/{id}',
                'description' => 'Detail layanan',
            ],
            [
                'method' => 'POST',
                'path' => '/api/services',
                'description' => 'Buat layanan baru',
            ],
            [
                'method' => 'PUT/PATCH',
                'path' => '/api/services/{id}',
                'description' => 'Update layanan',
            ],
            [
                'method' => 'DELETE',
                'path' => '/api/services/{id}',
                'description' => 'Hapus layanan',
            ],
            [
                'method' => 'GET',
                'path' => '/api/booking-status/{id}',
                'description' => 'Cek status booking',
            ],
            [
                'method' => 'GET',
                'path' => '/api/rajaongkir/provinces',
                'description' => 'Daftar provinsi RajaOngkir',
            ],
            [
                'method' => 'GET',
                'path' => '/api/rajaongkir/cities/{provinceId}',
                'description' => 'Daftar kota berdasarkan provinsi',
            ],
            [
                'method' => 'POST',
                'path' => '/api/rajaongkir/cost',
                'description' => 'Estimasi ongkir',
            ],
        ];

        return view('api/docs', [
            'endpoints' => $endpoints,
            'authHeader' => 'X-API-KEY: <your-api-key>',
        ]);
    }
}
