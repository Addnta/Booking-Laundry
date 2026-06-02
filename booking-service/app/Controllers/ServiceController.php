<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class ServiceController extends BaseController
{
    public function index()
    {
        $model = new ServiceModel();

        $data['services'] = $model->findAll();

        return view('dashboard/admin/services/index', $data);
    }

    public function create()
    {
        return view('dashboard/admin/services/create');
    }

    public function store()
{
    $model = new ServiceModel();

    $model->save([
        'nama_service' => $this->request->getPost('nama_service'),
        'harga' => $this->request->getPost('harga')
    ]);

    return redirect()->to('/admin/dashboard')
    ->with('success', 'Service berhasil ditambahkan');
}

public function edit($id)
{
    $model = new ServiceModel();

    $data['service'] = $model->find($id);

    return view('dashboard/admin/services/edit', $data);
}

public function update($id)
{
    $model = new ServiceModel();

    $model->update($id, [
        'nama_service' => $this->request->getPost('nama_service'),
        'harga' => $this->request->getPost('harga')
    ]);

    return redirect()->to('/admin/dashboard')
        ->with('success', 'Service berhasil diupdate');
}

public function delete($id)
{
    $model = new \App\Models\ServiceModel();

    $model->where('id_service', $id)->delete();

    return redirect()->to('/admin/dashboard')
        ->with('success', 'Service berhasil dihapus');
}
}