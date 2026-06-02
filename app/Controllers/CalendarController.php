<?php

namespace App\Controllers;

use App\Libraries\GoogleCalendarService;
use App\Models\BookingModel;

class CalendarController extends BaseController
{
    public function sync($id)
    {
        $bookingModel = new BookingModel();
        $booking = $bookingModel
            ->select('bookings.*, services.name as service_name, schedules.date, schedules.time_slot')
            ->join('services', 'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->where('bookings.id', $id)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan');
        }

        if ($booking['user_id'] !== session()->get('user_id') && session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $service = new GoogleCalendarService();

        if (!$service->isConfigured()) {
            return redirect()->back()->with('error', 'Google Calendar belum dikonfigurasi');
        }

        $eventId = $service->createEvent($booking);

        if (!$eventId) {
            return redirect()->back()->with('error', 'Gagal sinkronisasi ke Google Calendar');
        }

        $bookingModel->update($booking['id'], ['google_event_id' => $eventId]);

        return redirect()->back()->with('success', 'Jadwal berhasil disinkronkan ke Google Calendar');
    }

    public function downloadIcs($id)
    {
        $bookingModel = new BookingModel();
        $booking = $bookingModel
            ->select('bookings.*, services.name as service_name, schedules.date, schedules.time_slot')
            ->join('services', 'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->where('bookings.id', $id)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan');
        }

        if ($booking['user_id'] !== session()->get('user_id') && session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $uid = 'booking-' . $booking['id'] . '@laundry-booking.local';
        $summary = 'Laundry Booking - ' . $booking['booking_code'];
        $description = 'Booking untuk layanan ' . ($booking['service_name'] ?? '') . '. Status: ' . $booking['booking_status'];
        $location = $booking['destination_address'] ?? 'Laundry outlet';
        $times = explode('-', $booking['time_slot'] ?? '09:00-11:00');
        $startTime = trim($times[0]) ?: '09:00';
        $endTime = trim($times[1]) ?: date('H:i', strtotime($startTime . ' +2 hours'));
        $start = date('Ymd\THis', strtotime($booking['date'] . ' ' . $startTime . ':00'));
        $end = date('Ymd\THis', strtotime($booking['date'] . ' ' . $endTime . ':00'));

        $ics = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Laundry Booking//EN\r\nCALSCALE:GREGORIAN\r\nBEGIN:VEVENT\r\nUID:$uid\r\nSUMMARY:$summary\r\nDESCRIPTION:$description\r\nDTSTART:$start\r\nDTEND:$end\r\nLOCATION:$location\r\nEND:VEVENT\r\nEND:VCALENDAR";

        return $this->response
            ->setHeader('Content-Type', 'text/calendar; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="booking-' . $booking['booking_code'] . '.ics"')
            ->setBody($ics);
    }
}
