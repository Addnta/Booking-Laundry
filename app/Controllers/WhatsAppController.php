<?php

namespace App\Controllers;

use App\Libraries\WhatsApp;
use App\Models\BookingModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class WhatsAppController extends BaseController
{
    protected WhatsApp $whatsappService;
    protected BookingModel $bookingModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->whatsappService = new WhatsApp();
        $this->bookingModel = new BookingModel();
        $this->userModel = new UserModel();
    }

    /**
     * Send WhatsApp message to a user
     */
    public function send(): ResponseInterface
    {
        if (!$this->request->isAjax()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Forbidden']);
        }

        $phoneNumber = $this->request->getPost('phone');
        $message = $this->request->getPost('message');

        if (empty($phoneNumber) || empty($message)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Phone number and message are required'
            ]);
        }

        $result = $this->whatsappService->sendMessage($phoneNumber, $message);

        if ($result['success']) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => $result['data']
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => $result['message'] ?? 'Failed to send message'
        ]);
    }

    /**
     * Send booking confirmation to customer
     */
    public function sendBookingConfirmation($bookingId): ResponseInterface
    {
        $booking = $this->bookingModel->find($bookingId);

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Booking not found']);
        }

        $customer = $this->userModel->find($booking['customer_id']);

        if (!$customer || empty($customer['phone'])) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Customer phone not found']);
        }

        $message = $this->whatsappService->buildBookingConfirmationMessage($booking, $customer);
        $result = $this->whatsappService->sendMessage($customer['phone'], $message);

        if ($result['success']) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking confirmation sent to customer'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => $result['message'] ?? 'Failed to send confirmation'
        ]);
    }

    /**
     * Send booking reminder
     */
    public function sendReminder($bookingId): ResponseInterface
    {
        $booking = $this->bookingModel->find($bookingId);

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Booking not found']);
        }

        $customer = $this->userModel->find($booking['customer_id']);

        if (!$customer || empty($customer['phone'])) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Customer phone not found']);
        }

        $message = $this->whatsappService->buildReminderMessage($booking, $customer);
        $result = $this->whatsappService->sendMessage($customer['phone'], $message);

        if ($result['success']) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Reminder sent to customer'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => $result['message'] ?? 'Failed to send reminder'
        ]);
    }

    /**
     * Send booking completion notification
     */
    public function sendCompletion($bookingId): ResponseInterface
    {
        $booking = $this->bookingModel->find($bookingId);

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Booking not found']);
        }

        $customer = $this->userModel->find($booking['customer_id']);

        if (!$customer || empty($customer['phone'])) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Customer phone not found']);
        }

        $message = $this->whatsappService->buildCompletionMessage($booking, $customer);
        $result = $this->whatsappService->sendMessage($customer['phone'], $message);

        if ($result['success']) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Completion notification sent to customer'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => $result['message'] ?? 'Failed to send completion notification'
        ]);
    }

    /**
     * Send custom message to multiple users
     */
    public function sendBulk(): ResponseInterface
    {
        if (!$this->request->isAjax()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Forbidden']);
        }

        $recipients = $this->request->getPost('recipients');
        $message = $this->request->getPost('message');

        if (empty($recipients) || empty($message)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Recipients and message are required'
            ]);
        }

        $recipients = is_array($recipients) ? $recipients : explode(',', $recipients);
        $results = [];

        foreach ($recipients as $phoneNumber) {
            $result = $this->whatsappService->sendMessage(trim($phoneNumber), $message);
            $results[] = [
                'phone' => $phoneNumber,
                'success' => $result['success'],
                'message' => $result['message'] ?? ''
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Bulk messages processed',
            'results' => $results
        ]);
    }

    /**
     * Get WhatsApp API status
     */
    public function status(): ResponseInterface
    {
        $status = $this->whatsappService->getStatus();

        return $this->response->setJSON([
            'status' => $status['connected'] ? 'connected' : 'disconnected',
            'data' => $status
        ]);
    }
}
