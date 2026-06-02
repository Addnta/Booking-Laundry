<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function loginProcess()
    {
        $session = session();

        $model = new UserModel();

        $email = $this->request->getPost('email');

        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user) {
            if (($user['status'] ?? 'inactive') !== 'active') {
                return redirect()->back()->with(
                    'error',
                    'Akun Anda nonaktif. Hubungi admin.'
                );
            }

            if (password_verify($password, $user['password'])) {

                $session->set([
                    'user_id'   => $user['id'],
                    'name'      => $user['name'],
                    'role'      => $user['role'],
                    'logged_in' => true
                ]);

                // redirect berdasarkan role
                if ($user['role'] == 'admin') {

                    return redirect()->to('/admin/dashboard');

                } elseif ($user['role'] == 'staff') {

                    return redirect()->to('/staff/dashboard');

                } else {

                    return redirect()->to('/customer/dashboard');
                }

            } else {

                return redirect()->back()->with(
                    'error',
                    'Password salah'
                );
            }

        } else {

            return redirect()->back()->with(
                'error',
                'Email tidak ditemukan'
            );
        }
    }

    public function register()
    {
        return view('auth/register');
    }

    public function registerProcess()
    {
        $model = new UserModel();

        $data = [

            'name' => $this->request->getPost('name'),

            'email' => $this->request->getPost('email'),

            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),

            'role' => 'customer',

            'phone' => $this->request->getPost('phone'),

            'status' => 'active'
        ];

        $model->save($data);

        return redirect()->to('/login')
            ->with('success', 'Register berhasil');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
