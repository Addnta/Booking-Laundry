<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class BookingApiController extends BaseController
{
    protected BookingModel $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    public function status($id)
    {
        $booking = $this->bookingModel
            ->select('bookings.*, services.name as service_name, schedules.date, schedules.time_slot')
            ->join('services', 'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->where('bookings.id', $id)
            ->first();

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Booking not found',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'booking_code' => $booking['booking_code'],
                'booking_status' => $booking['booking_status'],
                'payment_status' => $booking['payment_status'],
                'service_name' => $booking['service_name'] ?? null,
                'date' => $booking['date'] ?? null,
                'time_slot' => $booking['time_slot'] ?? null,
            ],
        ]);
    }

    public function show($id)
    {
        return $this->status($id);
    }
}
