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
        $model = new UserModel();

        $nama = $this->request->getPost('nama');
        $password = $this->request->getPost('password');

        $user = $model->where('nama', $nama)->first();

        if ($user) {

            if (password_verify($password, $user['password'])) {

                session()->set([
                    'id' => $user['id'],
                    'nama' => $user['nama'],
                    'role' => $user['role'],
                    'logged_in' => true
                ]);

                if ($user['role'] == 'admin') {
                    return redirect()->to('/admin/dashboard');
                }

                return redirect()->to('/customer/dashboard');
            }
        }

        return redirect()->back()->with('error', 'Login gagal');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}