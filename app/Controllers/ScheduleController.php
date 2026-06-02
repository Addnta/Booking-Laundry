<?php

namespace App\Controllers;

use App\Models\ScheduleModel;
use App\Models\ServiceModel;

class ScheduleController extends BaseController
{
    public function index()
    {
        $model = new ScheduleModel();

        $model = $model->select('schedules.*, services.name as service_name')
            ->join('services', 'services.id = schedules.service_id')
            ->orderBy('date', 'ASC');

        $perPage = 10;
        $data['schedules'] = $model->paginate($perPage);
        $data['pager'] = $model->pager;

        return view('schedules/index', $data);
    }

    public function create()
    {
        $serviceModel = new ServiceModel();
        $data['services'] = $serviceModel->findAll();
        return view('schedules/create', $data);
    }

    public function store()
    {
        $rules = [
            'service_id' => 'required|numeric',
            'date' => 'required|valid_date[Y-m-d]',
            'time_slot' => 'required',
            'capacity' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new ScheduleModel();
        $model->save([
            'service_id' => $this->request->getPost('service_id'),
            'date' => $this->request->getPost('date'),
            'time_slot' => $this->request->getPost('time_slot'),
            'capacity' => (int) $this->request->getPost('capacity'),
        ]);

        return redirect()->to('/admin/schedules')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $model = new ScheduleModel();
        $serviceModel = new ServiceModel();

        $data['schedule'] = $model->find($id);
        if (!$data['schedule']) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan');
        }
        $data['services'] = $serviceModel->findAll();

        return view('schedules/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'service_id' => 'required|numeric',
            'date' => 'required|valid_date[Y-m-d]',
            'time_slot' => 'required',
            'capacity' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new ScheduleModel();
        $schedule = $model->find($id);
        if (!$schedule) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan');
        }

        $model->update($id, [
            'service_id' => $this->request->getPost('service_id'),
            'date' => $this->request->getPost('date'),
            'time_slot' => $this->request->getPost('time_slot'),
            'capacity' => (int) $this->request->getPost('capacity'),
        ]);

        return redirect()->to('/admin/schedules')->with('success', 'Jadwal berhasil diperbarui');
    }

    public function delete($id)
    {
        $model = new ScheduleModel();
        $model->delete($id);
        return redirect()->to('/admin/schedules')->with('success', 'Jadwal berhasil dihapus');
    }
}
