<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class ServiceController extends BaseController
{
    public function customerIndex()
    {
        $model = new ServiceModel();

        $data['services'] = $model->findAll();

        return view('services/customer_index', $data);
    }

    public function index()
    {
        $model = new ServiceModel();

        $data['services'] = $model->findAll();

        return view('services/index', $data);
    }

    public function create()
    {
        return view('services/create');
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [

            'name' => 'required',

            'price' => 'required|numeric',

            'duration' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {

            return redirect()->back()
                ->withInput()
                ->with(
                    'errors',
                    $validation->getErrors()
                );
        }

        $model = new ServiceModel();

        $photo = $this->request->getFile('photo');

        $photoName = '';

        if ($photo && $photo->isValid()) {

            $photoName = $photo->getRandomName();

            $photo->move(
                'uploads/services',
                $photoName
            );
        }

        $model->save([

            'name' => $this->request->getPost('name'),

            'description' => $this->request->getPost('description'),

            'price' => $this->request->getPost('price'),

            'duration' => $this->request->getPost('duration'),

            'photo' => $photoName
        ]);

        return redirect()->to('/admin/services')
            ->with(
                'success',
                'Service berhasil ditambahkan'
            );
    }

    public function edit($id)
    {
        $model = new ServiceModel();

        $data['service'] = $model->find($id);

        return view('services/edit', $data);
    }

    public function update($id)
    {
        $model = new ServiceModel();

        $service = $model->find($id);

        $photo = $this->request->getFile('photo');

        $photoName = $service['photo'];

        if ($photo && $photo->isValid()) {

            if (
                $service['photo'] &&
                file_exists(
                    'uploads/services/' . $service['photo']
                )
            ) {
                unlink(
                    'uploads/services/' . $service['photo']
                );
            }

            $photoName = $photo->getRandomName();

            $photo->move(
                'uploads/services',
                $photoName
            );
        }

        $model->update($id, [

            'name' => $this->request->getPost('name'),

            'description' => $this->request->getPost('description'),

            'price' => $this->request->getPost('price'),

            'duration' => $this->request->getPost('duration'),

            'photo' => $photoName
        ]);

        return redirect()->to('/admin/services');
    }

    public function delete($id)
    {
        $model = new ServiceModel();

        $service = $model->find($id);

        if (
            $service['photo'] &&
            file_exists(
                'uploads/services/' . $service['photo']
            )
        ) {
            unlink(
                'uploads/services/' . $service['photo']
            );
        }

        $model->delete($id);

        return redirect()->to('/admin/services');
    }
}
