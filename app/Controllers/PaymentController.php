<?php

namespace App\Controllers;

use App\Libraries\NotificationService;
use App\Models\BookingModel;
use App\Models\PaymentModel;

class PaymentController extends BaseController
{
    protected BookingModel $bookingModel;
    protected PaymentModel $paymentModel;
    protected NotificationService $notificationService;
    protected $midtransConfig;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->paymentModel = new PaymentModel();
        $this->notificationService = new NotificationService();
        $this->midtransConfig = config('Midtrans');
    }

    public function checkout($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $booking = $this->bookingModel
            ->select('bookings.*, services.name as service_name')
            ->join('services', 'services.id = bookings.service_id')
            ->where('bookings.id', $id)
            ->first();

        if (!$booking || $booking['user_id'] !== session()->get('user_id')) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan');
        }

        if ($booking['payment_status'] === 'paid') {
            return redirect()->back()->with('success', 'Pembayaran sudah berhasil');
        }

        $payment = $this->paymentModel->where('booking_id', $booking['id'])->first();

        if (!$payment) {
            $orderId = 'ORDER-' . time() . '-' . $booking['id'];

            $this->paymentModel->insert([
                'booking_id' => $booking['id'],
                'order_id' => $orderId,
                'payment_type' => null,
                'transaction_status' => 'pending',
                'gross_amount' => $booking['total_price'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $payment = $this->paymentModel->find($this->paymentModel->getInsertID());
        }

        if (empty($this->midtransConfig->serverKey) || empty($this->midtransConfig->clientKey)) {
            return redirect()->back()->with('error', 'Konfigurasi Midtrans belum diatur. Isi midtrans.serverKey dan midtrans.clientKey di .env.');
        }

        if (empty($payment['snap_token'])) {
            try {
                $snapToken = $this->createMidtransSnapToken($booking, $payment);
                $this->paymentModel->update($payment['id'], ['snap_token' => $snapToken]);
                $payment['snap_token'] = $snapToken;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal membuat token pembayaran: ' . $e->getMessage());
            }
        }

        return view('payments/checkout', [
            'booking' => $booking,
            'payment' => $payment,
            'snapToken' => $payment['snap_token'],
            'clientKey' => $this->midtransConfig->clientKey,
        ]);
    }

    protected function createMidtransSnapToken(array $booking, array $payment): string
    {
        $requestBody = [
            'transaction_details' => [
                'order_id' => $payment['order_id'],
                'gross_amount' => (float) $booking['total_price'],
            ],
            'credit_card' => [
                'secure' => true,
            ],
            'customer_details' => [
                'first_name' => session()->get('name') ?? 'Customer',
                'email' => $this->getUserEmail($booking['user_id']),
            ],
            'item_details' => [[
                'id' => (string) $booking['service_id'],
                'price' => (float) $booking['total_price'],
                'quantity' => 1,
                'name' => 'Laundry ' . $booking['booking_code'],
            ]],
        ];

        $curl = curl_init($this->midtransConfig->apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->midtransConfig->serverKey . ':'),
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestBody));

        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($response === false || $statusCode !== 201) {
            $errorMessage = $response ?: $curlError ?: 'Unknown Midtrans error';
            $decodedError = json_decode($response, true);

            if (is_array($decodedError) && !empty($decodedError['error_messages'])) {
                $errorMessage = implode(' | ', $decodedError['error_messages']);
            }

            throw new \RuntimeException('Midtrans error: ' . $errorMessage);
        }

        $result = json_decode($response, true);

        if (empty($result['token'])) {
            throw new \RuntimeException('Tidak menerima token dari Midtrans.');
        }

        return $result['token'];
    }

    public function webhook()
    {
        $payload = json_decode($this->request->getBody(), true);

        if (empty($payload) || empty($payload['order_id'])) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Payload tidak valid']);
        }

        $payment = $this->paymentModel->where('order_id', $payload['order_id'])->first();

        if (!$payment) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Pembayaran tidak ditemukan']);
        }

        // Optional security check for official Midtrans callback
        if (!empty($payload['signature_key'])) {
            $expectedSignature = hash(
                'sha512',
                ($payload['order_id'] ?? '') .
                ($payload['status_code'] ?? '') .
                ($payload['gross_amount'] ?? '') .
                $this->midtransConfig->serverKey
            );

            if (!hash_equals($expectedSignature, (string) $payload['signature_key'])) {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => 'error',
                    'message' => 'Signature Midtrans tidak valid',
                ]);
            }
        }

        $transactionStatus = $payload['transaction_status'] ?? $payload['status'] ?? $payment['transaction_status'];
        $paidAt = $payload['transaction_time'] ?? date('Y-m-d H:i:s');
        $previousPaymentStatus = $payment['transaction_status'] ?? null;

        $this->paymentModel->update($payment['id'], [
            'payment_type' => $payload['payment_type'] ?? $payment['payment_type'],
            'transaction_status' => $transactionStatus,
            'paid_at' => $paidAt,
        ]);

        $bookingStatus = 'unpaid';
        if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
            $bookingStatus = 'paid';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $bookingStatus = 'failed';
        }

        $booking = $this->bookingModel->find($payment['booking_id']);

        if ($booking) {
            $oldBookingPaymentStatus = $booking['payment_status'] ?? null;
            $this->bookingModel->update($booking['id'], ['payment_status' => $bookingStatus]);

            // Send email/notification only when status changes (idempotent callbacks)
            if ($oldBookingPaymentStatus !== $bookingStatus || $previousPaymentStatus !== $transactionStatus) {
                if ($bookingStatus === 'paid') {
                    $this->notificationService->notify(
                        $booking['user_id'],
                        'payment',
                        'Pembayaran Berhasil - ' . $booking['booking_code'],
                        'Pembayaran booking ' . $booking['booking_code'] . ' berhasil. Pesanan Anda sedang kami proses.',
                        $booking['booking_code']
                    );
                } else {
                    $this->notificationService->notify(
                        $booking['user_id'],
                        'payment',
                        'Status Pembayaran ' . $booking['booking_code'],
                        'Status pembayaran booking ' . $booking['booking_code'] . ' sekarang ' . $bookingStatus . '.',
                        $booking['booking_code']
                    );
                }
            }
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function callback()
    {
        return $this->webhook();
    }

    protected function getUserEmail(int $userId): string
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);

        return $user['email'] ?? '';
    }
}
