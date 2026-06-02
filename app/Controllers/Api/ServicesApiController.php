<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ServiceModel;

class ServicesApiController extends BaseController
{
    protected ServiceModel $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
    }

    public function index()
    {
        $perPage = (int) ($this->request->getGet('per_page') ?: 10);
        $perPage = max(1, min(50, $perPage));

        $builder = $this->serviceModel->orderBy('created_at', 'DESC');
        $services = $builder->paginate($perPage);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $services,
            'meta' => [
                'current_page' => $this->serviceModel->pager->getCurrentPage(),
                'per_page' => $perPage,
                'total' => $this->serviceModel->pager->getTotal(),
                'page_count' => $this->serviceModel->pager->getPageCount(),
            ],
        ]);
    }

    public function show($id)
    {
        $service = $this->serviceModel->find($id);

        if (!$service) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Service not found',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $service,
        ]);
    }

    public function store()
    {
        $payload = $this->payload();
        $rules = [
            'name' => 'required|min_length[3]',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
        ];

        if (!$this->validateData($payload, $rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $photoName = null;
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            if (!is_dir('uploads/services')) {
                mkdir('uploads/services', 0775, true);
            }
            $photoName = $photo->getRandomName();
            $photo->move('uploads/services', $photoName);
        }

        $this->serviceModel->save([
            'name' => trim((string) $payload['name']),
            'description' => $payload['description'] ?? null,
            'price' => $payload['price'],
            'duration' => $payload['duration'],
            'photo' => $photoName,
        ]);

        return $this->response->setStatusCode(201)->setJSON([
            'status' => 'success',
            'message' => 'Service created',
            'data' => $this->serviceModel->find($this->serviceModel->getInsertID()),
        ]);
    }

    public function update($id)
    {
        $service = $this->serviceModel->find($id);
        if (!$service) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Service not found',
            ]);
        }

        $payload = $this->payload();
        $rules = [
            'name' => 'required|min_length[3]',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
        ];

        if (!$this->validateData($payload, $rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $photoName = $service['photo'] ?? null;
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            if (!empty($photoName) && file_exists('uploads/services/' . $photoName)) {
                unlink('uploads/services/' . $photoName);
            }
            if (!is_dir('uploads/services')) {
                mkdir('uploads/services', 0775, true);
            }
            $photoName = $photo->getRandomName();
            $photo->move('uploads/services', $photoName);
        }

        $this->serviceModel->update($id, [
            'name' => trim((string) $payload['name']),
            'description' => $payload['description'] ?? null,
            'price' => $payload['price'],
            'duration' => $payload['duration'],
            'photo' => $photoName,
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Service updated',
            'data' => $this->serviceModel->find($id),
        ]);
    }

    public function delete($id)
    {
        $service = $this->serviceModel->find($id);
        if (!$service) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Service not found',
            ]);
        }

        if (!empty($service['photo']) && file_exists('uploads/services/' . $service['photo'])) {
            unlink('uploads/services/' . $service['photo']);
        }

        $this->serviceModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Service deleted',
        ]);
    }

    protected function payload(): array
    {
        $contentType = strtolower((string) $this->request->getHeaderLine('Content-Type'));

        if (str_contains($contentType, 'application/json')) {
            $data = json_decode($this->request->getBody(), true);
            return is_array($data) ? $data : [];
        }

        $rawInput = $this->request->getRawInput();
        if (!empty($rawInput)) {
            return $rawInput;
        }

        return $this->request->getPost() ?: [];
    }
}
