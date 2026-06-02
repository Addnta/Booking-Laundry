<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $role = $this->request->getGet('role');
        $status = $this->request->getGet('status');

        $query = $userModel->orderBy('created_at', 'DESC');
        if (!empty($role)) {
            $query->where('role', $role);
        }
        if (!empty($status)) {
            $query->where('status', $status);
        }

        $data['users'] = $query->paginate(15);
        $data['pager'] = $query->pager;
        $data['filters'] = ['role' => $role, 'status' => $status];

        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[admin,staff,customer]',
            'status' => 'required|in_list[active,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userModel->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'phone' => $this->request->getPost('phone'),
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan');
    }

    public function toggleStatus($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        if ((int) $user['id'] === (int) session()->get('user_id')) {
            return redirect()->back()->with('error', 'Tidak bisa menonaktifkan akun sendiri');
        }

        $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
        $userModel->update($id, ['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status user berhasil diubah menjadi ' . $newStatus);
    }
}
