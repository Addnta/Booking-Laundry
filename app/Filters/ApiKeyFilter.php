<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiKeyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $expectedKey = (string) env('api.key', '');
        if ($expectedKey === '') {
            return null;
        }

        $providedKey = (string) $request->getHeaderLine('X-API-KEY');
        if ($providedKey === '') {
            $authorization = (string) $request->getHeaderLine('Authorization');
            if (str_starts_with($authorization, 'Bearer ')) {
                $providedKey = trim(substr($authorization, 7));
            }
        }

        if ($providedKey === '' && $request->getGet('api_key')) {
            $providedKey = (string) $request->getGet('api_key');
        }

        if (!hash_equals($expectedKey, $providedKey)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized. Invalid API key.',
                ]);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
