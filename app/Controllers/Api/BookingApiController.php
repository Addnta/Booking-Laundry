<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class BookingApiController extends BaseController
{
    public function status($id)
    {
        $bookingModel = new BookingModel();

        $booking = $bookingModel->find($id);

        if (!$booking) {

            return $this->response->setJSON([
                'message' => 'Booking not found'
            ]);
        }

        return $this->response->setJSON([
            'booking_code' => $booking['booking_code'],
            'booking_status' => $booking['booking_status'],
            'payment_status' => $booking['payment_status']
        ]);
    }
}