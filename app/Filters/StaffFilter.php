<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;

use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\Filters\FilterInterface;

class StaffFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    )
    {
        if (!session()->get('logged_in')) {

            return redirect()->to('/login');
        }

        if (session()->get('role') != 'staff') {

            return redirect()->to('/login')
                ->with('error', 'Akses ditolak');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
    }
}