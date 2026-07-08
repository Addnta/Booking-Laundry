<?php

/**
 * WhatsApp Integration Examples
 * 
 * File ini berisi contoh implementasi WhatsApp di berbagai skenario
 * Copy dan modifikasi sesuai kebutuhan Anda
 */

namespace App\Examples;

use App\Libraries\WhatsApp;
use App\Models\BookingModel;
use App\Models\UserModel;

class WhatsAppExamples
{
    protected WhatsApp $whatsapp;
    protected BookingModel $bookingModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->whatsapp = new WhatsApp();
        $this->bookingModel = new BookingModel();
        $this->userModel = new UserModel();
    }

    /**
     * CONTOH 1: Mengirim pesan sederhana
     */
    public function example1_sendSimpleMessage()
    {
        $result = $this->whatsapp->sendMessage(
            '6281234567890',
            'Halo, ini pesan test dari Booking Service'
        );

        if ($result['success']) {
            echo "✅ Pesan berhasil dikirim!";
            echo "Message ID: " . $result['data']['messageId'];
        } else {
            echo "❌ Gagal mengirim pesan: " . $result['message'];
        }
    }

    /**
     * CONTOH 2: Mengirim konfirmasi booking otomatis saat booking dibuat
     */
    public function example2_autoConfirmationOnBooking($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        $customer = $this->userModel->find($booking['customer_id']);

        if (!$customer['phone']) {
            return ['success' => false, 'message' => 'No phone number'];
        }

        // Build message
        $message = $this->whatsapp->buildBookingConfirmationMessage($booking, $customer);

        // Send
        $result = $this->whatsapp->sendMessage($customer['phone'], $message);

        if ($result['success']) {
            // Update database
            $this->bookingModel->update($bookingId, [
                'confirmation_sent' => 1,
                'confirmation_sent_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $result;
    }

    /**
     * CONTOH 3: Mengirim reminder ke semua booking yang akan dimulai besok
     */
    public function example3_sendReminderTomorrow()
    {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        // Get all bookings scheduled for tomorrow
        $bookings = $this->bookingModel
            ->where('DATE(booking_date)', $tomorrow)
            ->where('status', 'confirmed')
            ->findAll();

        $successCount = 0;
        $failureCount = 0;

        foreach ($bookings as $booking) {
            $customer = $this->userModel->find($booking['customer_id']);

            if (!$customer['phone']) {
                $failureCount++;
                continue;
            }

            $message = $this->whatsapp->buildReminderMessage($booking, $customer);
            $result = $this->whatsapp->sendMessage($customer['phone'], $message);

            if ($result['success']) {
                $successCount++;
                $this->bookingModel->update($booking['id'], [
                    'reminder_sent' => 1,
                    'reminder_sent_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $failureCount++;
                log_message('error', 'Reminder failed for booking ' . $booking['id']);
            }

            // Delay to avoid rate limiting
            sleep(1);
        }

        return [
            'success' => true,
            'sent' => $successCount,
            'failed' => $failureCount,
            'total' => count($bookings)
        ];
    }

    /**
     * CONTOH 4: Mengirim notifikasi selesai untuk booking
     */
    public function example4_sendCompletionNotification($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        $customer = $this->userModel->find($booking['customer_id']);

        if (!$customer['phone']) {
            return ['success' => false, 'message' => 'No phone number'];
        }

        $message = $this->whatsapp->buildCompletionMessage($booking, $customer);
        $result = $this->whatsapp->sendMessage($customer['phone'], $message);

        if ($result['success']) {
            $this->bookingModel->update($bookingId, [
                'completion_notified' => 1,
                'completion_notified_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $result;
    }

    /**
     * CONTOH 5: Mengirim pesan custom dengan data dinamis
     */
    public function example5_sendCustomMessage($customerId, $subject, $content)
    {
        $customer = $this->userModel->find($customerId);

        if (!$customer['phone']) {
            return ['success' => false, 'message' => 'No phone number'];
        }

        // Format pesan dengan bold untuk subject
        $message = "*$subject*\n\n$content";

        return $this->whatsapp->sendMessage($customer['phone'], $message);
    }

    /**
     * CONTOH 6: Mengirim pesan ke multiple customers (bulk)
     */
    public function example6_sendBulkMessage($customerIds, $message)
    {
        $results = [];
        $successCount = 0;

        foreach ($customerIds as $customerId) {
            $customer = $this->userModel->find($customerId);

            if (!$customer['phone']) {
                $results[] = [
                    'customer_id' => $customerId,
                    'success' => false,
                    'message' => 'No phone number'
                ];
                continue;
            }

            $result = $this->whatsapp->sendMessage($customer['phone'], $message);

            $results[] = [
                'customer_id' => $customerId,
                'phone' => $customer['phone'],
                'success' => $result['success'],
                'message' => $result['message']
            ];

            if ($result['success']) {
                $successCount++;
            }

            // Delay untuk menghindari rate limiting
            sleep(1);
        }

        return [
            'total' => count($customerIds),
            'success' => $successCount,
            'failed' => count($customerIds) - $successCount,
            'details' => $results
        ];
    }

    /**
     * CONTOH 7: Cek status koneksi API
     */
    public function example7_checkApiStatus()
    {
        $status = $this->whatsapp->getStatus();

        if ($status['connected']) {
            echo "✅ WhatsApp API Connected";
            return true;
        } else {
            echo "❌ WhatsApp API Disconnected";
            echo "Error: " . $status['error'];
            return false;
        }
    }

    /**
     * CONTOH 8: Integrasi dengan UpdateStatus di BookingController
     */
    public function example8_integrationWithBookingStatus($bookingId, $newStatus)
    {
        $booking = $this->bookingModel->find($bookingId);
        $customer = $this->userModel->find($booking['customer_id']);

        if (!$customer['phone']) {
            return false;
        }

        $messages = [
            'pending' => 'Booking Anda sedang menunggu konfirmasi dari admin.',
            'confirmed' => $this->whatsapp->buildBookingConfirmationMessage($booking, $customer),
            'processing' => 'Booking Anda sedang diproses. Terima kasih atas kesabaran Anda!',
            'completed' => $this->whatsapp->buildCompletionMessage($booking, $customer),
            'cancelled' => 'Booking Anda telah dibatalkan. Hubungi kami untuk informasi lebih lanjut.'
        ];

        if (!isset($messages[$newStatus])) {
            return false;
        }

        $result = $this->whatsapp->sendMessage(
            $customer['phone'],
            $messages[$newStatus]
        );

        return $result['success'];
    }

    /**
     * CONTOH 9: Custom notification dengan template
     */
    public function example9_notificationWithTemplate($customerId, $title, $data)
    {
        $customer = $this->userModel->find($customerId);

        if (!$customer['phone']) {
            return ['success' => false, 'message' => 'No phone number'];
        }

        // Build custom message
        $message = "*$title*\n\n";
        foreach ($data as $key => $value) {
            $message .= "• $key: $value\n";
        }

        return $this->whatsapp->sendMessage($customer['phone'], $message);
    }

    /**
     * CONTOH 10: Error handling dan retry
     */
    public function example10_sendWithRetry($phone, $message, $maxRetries = 3)
    {
        $attempt = 1;

        while ($attempt <= $maxRetries) {
            $result = $this->whatsapp->sendMessage($phone, $message);

            if ($result['success']) {
                return [
                    'success' => true,
                    'attempts' => $attempt,
                    'data' => $result['data']
                ];
            }

            log_message('warning', "Attempt $attempt failed for phone $phone");

            if ($attempt < $maxRetries) {
                // Exponential backoff: wait 2, 4, 8 seconds
                sleep(2 ** $attempt);
            }

            $attempt++;
        }

        return [
            'success' => false,
            'attempts' => $maxRetries,
            'message' => 'Failed after ' . $maxRetries . ' attempts',
            'last_error' => $result['message']
        ];
    }
}

/**
 * ============================================================
 * IMPLEMENTASI DI CONTROLLER
 * ============================================================
 * 
 * Berikut contoh cara menggunakan WhatsApp di controller:
 * 
 * namespace App\Controllers;
 * use App\Libraries\WhatsApp;
 * 
 * class BookingController extends BaseController
 * {
 *     public function confirmBooking($id)
 *     {
 *         $whatsapp = new WhatsApp();
 *         $booking = $this->bookingModel->find($id);
 *         $customer = $this->userModel->find($booking['customer_id']);
 *         
 *         // Send WhatsApp
 *         $message = $whatsapp->buildBookingConfirmationMessage($booking, $customer);
 *         $whatsapp->sendMessage($customer['phone'], $message);
 *         
 *         // Update status
 *         $this->bookingModel->update($id, ['status' => 'confirmed']);
 *         
 *         return redirect()->back()->with('success', 'Booking confirmed');
 *     }
 * }
 * 
 * ============================================================
 * IMPLEMENTASI DI MODEL / SERVICE
 * ============================================================
 * 
 * namespace App\Models;
 * use App\Libraries\WhatsApp;
 * 
 * class BookingModel extends Model
 * {
 *     protected $whatsapp;
 *     
 *     public function __construct()
 *     {
 *         parent::__construct();
 *         $this->whatsapp = new WhatsApp();
 *     }
 *     
 *     public function insertAndNotify($data)
 *     {
 *         // Insert booking
 *         $bookingId = $this->insert($data, true);
 *         
 *         // Send notification
 *         $booking = $this->find($bookingId);
 *         $customer = (new UserModel())->find($booking['customer_id']);
 *         $message = $this->whatsapp->buildBookingConfirmationMessage($booking, $customer);
 *         $this->whatsapp->sendMessage($customer['phone'], $message);
 *         
 *         return $bookingId;
 *     }
 * }
 * 
 * ============================================================
 */
