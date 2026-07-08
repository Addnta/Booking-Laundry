<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;

class WhatsApp
{
    /**
     * PetaPod API Base URL
     */
    protected string $baseUrl = 'https://servicelaundry-05a3.sg-2.podo.top/dashboard';

    /**
     * PetaPod API Authentication Key
     */
    protected string $apiKey = '6ab9f50e73e50ce363c5793b8f5baf67528b5384fff1cb29';

    /**
     * HTTP Client
     */
    protected CURLRequest $client;

    /**
     * Business Phone Number (from PetaPod)
     */
    protected string $businessPhone = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = \Config\Services::curlrequest();
        
        // Load PetaPod configuration if exists
        $config = config('WhatsApp');
        if (!empty($config)) {
            $this->businessPhone = $config->businessPhone ?? '';
        }
    }

    /**
     * Send WhatsApp message via PetaPod API
     *
     * @param string $phoneNumber Recipient phone number (format: 62812345678)
     * @param string $message Message text
     * @param array $media Optional media attachment
     * @return array Result with success status and response data
     */
    public function sendMessage(string $phoneNumber, string $message, ?array $media = null): array
    {
        try {
            // Format phone number (remove +, add 62 if needed)
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            $payload = [
                'phone' => $phoneNumber,
                'message' => $message,
                'media' => $media
            ];

            $response = $this->client->request(
                'POST',
                $this->baseUrl . '/api/send-message',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ],
                    'json' => $payload,
                    'timeout' => 30
                ]
            );

            $statusCode = $response->getStatusCode();
            $body = $response->getJSON();

            if ($statusCode >= 200 && $statusCode < 300) {
                return [
                    'success' => true,
                    'data' => $body,
                    'message' => 'Message sent successfully'
                ];
            }

            return [
                'success' => false,
                'message' => $body->message ?? 'Failed to send message',
                'error' => $body
            ];
        } catch (\Exception $e) {
            log_message('error', 'WhatsApp Send Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error sending message: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send message with template
     *
     * @param string $phoneNumber Recipient phone number
     * @param string $templateName Template name
     * @param array $parameters Template parameters
     * @return array Result array
     */
    public function sendTemplate(string $phoneNumber, string $templateName, array $parameters = []): array
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            $payload = [
                'phone' => $phoneNumber,
                'template' => $templateName,
                'parameters' => $parameters
            ];

            $response = $this->client->request(
                'POST',
                $this->baseUrl . '/api/send-template',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ],
                    'json' => $payload,
                    'timeout' => 30
                ]
            );

            $statusCode = $response->getStatusCode();
            $body = $response->getJSON();

            if ($statusCode >= 200 && $statusCode < 300) {
                return [
                    'success' => true,
                    'data' => $body,
                    'message' => 'Template sent successfully'
                ];
            }

            return [
                'success' => false,
                'message' => $body->message ?? 'Failed to send template',
                'error' => $body
            ];
        } catch (\Exception $e) {
            log_message('error', 'WhatsApp Template Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error sending template: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get API status
     *
     * @return array Status information
     */
    public function getStatus(): array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->baseUrl . '/api/status',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Accept' => 'application/json'
                    ],
                    'timeout' => 10
                ]
            );

            $statusCode = $response->getStatusCode();
            $body = $response->getJSON();

            if ($statusCode >= 200 && $statusCode < 300) {
                return [
                    'connected' => true,
                    'data' => $body
                ];
            }

            return [
                'connected' => false,
                'data' => $body
            ];
        } catch (\Exception $e) {
            log_message('error', 'WhatsApp Status Error: ' . $e->getMessage());

            return [
                'connected' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to international format
     *
     * @param string $phone Raw phone number
     * @return string Formatted phone number
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // Remove leading 0 if exists
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        // Add country code if not present
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Build booking confirmation message
     *
     * @param array $booking Booking data
     * @param array $customer Customer data
     * @return string Formatted message
     */
    public function buildBookingConfirmationMessage(array $booking, array $customer): string
    {
        $message = "Halo {$customer['name']}, 👋\n\n";
        $message .= "Terima kasih telah melakukan pemesanan! ✅\n";
        $message .= "Booking Anda telah dikonfirmasi.\n\n";
        $message .= "📋 Detail Pesanan:\n";
        $message .= "Kode Booking: {$booking['booking_code']}\n";
        $message .= "Tanggal: " . date('d/m/Y H:i', strtotime($booking['booking_date'])) . "\n";
        
        if (!empty($booking['service_name'])) {
            $message .= "Layanan: {$booking['service_name']}\n";
        }
        
        if (!empty($booking['total_price'])) {
            $message .= "Total: Rp " . number_format($booking['total_price'], 0, ',', '.') . "\n";
        }

        $message .= "\n📍 Lokasi Pengambilan:\n{$booking['pickup_location']}\n";
        $message .= "\n⏰ Estimasi:\n" . ($booking['estimated_completion'] ?? 'Segera') . "\n\n";
        $message .= "Hubungi kami jika ada pertanyaan.\n";
        $message .= "Terima kasih! 🙏";

        return $message;
    }

    /**
     * Build booking reminder message
     *
     * @param array $booking Booking data
     * @param array $customer Customer data
     * @return string Formatted message
     */
    public function buildReminderMessage(array $booking, array $customer): string
    {
        $message = "Halo {$customer['name']}, 👋\n\n";
        $message .= "Pengingat: Pesanan Anda sedang diproses! ⏳\n\n";
        $message .= "📋 Detail Pesanan:\n";
        $message .= "Kode Booking: {$booking['booking_code']}\n";
        $message .= "Status: " . ucfirst($booking['status']) . "\n";
        
        if (!empty($booking['estimated_completion'])) {
            $message .= "Estimasi Selesai: {$booking['estimated_completion']}\n";
        }

        $message .= "\nTerima kasih atas kepercayaan Anda! 🙏";

        return $message;
    }

    /**
     * Build booking completion message
     *
     * @param array $booking Booking data
     * @param array $customer Customer data
     * @return string Formatted message
     */
    public function buildCompletionMessage(array $booking, array $customer): string
    {
        $message = "Halo {$customer['name']}, 👋\n\n";
        $message .= "Pesanan Anda telah selesai! ✅\n\n";
        $message .= "📋 Detail Pesanan:\n";
        $message .= "Kode Booking: {$booking['booking_code']}\n";
        $message .= "Status: Selesai\n";
        $message .= "Tanggal Selesai: " . date('d/m/Y H:i') . "\n";

        if (!empty($booking['total_price'])) {
            $message .= "Total: Rp " . number_format($booking['total_price'], 0, ',', '.') . "\n";
        }

        $message .= "\nPesanan siap diambil. Silakan hubungi kami untuk pengambilan.\n";
        $message .= "Terima kasih! 🙏";

        return $message;
    }

    /**
     * Send custom notification message
     *
     * @param string $phoneNumber Recipient phone number
     * @param string $title Notification title
     * @param string $message Notification message
     * @return array Result array
     */
    public function sendNotification(string $phoneNumber, string $title, string $message): array
    {
        $formattedMessage = "*$title*\n\n$message";
        return $this->sendMessage($phoneNumber, $formattedMessage);
    }
}
