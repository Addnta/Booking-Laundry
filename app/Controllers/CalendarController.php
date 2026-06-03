<?php

namespace App\Controllers;

use App\Libraries\GoogleCalendarService;
use App\Models\BookingModel;

class CalendarController extends BaseController
{
    protected function escapeIcsValue(?string $value): string
    {
        $value = (string) $value;
        $value = str_replace(['\\', ';', ','], ['\\\\', '\;', '\,'], $value);
        return preg_replace("/\r\n|\r|\n/", '\n', $value) ?? '';
    }

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
        $summary = $this->escapeIcsValue('Laundry Booking - ' . $booking['booking_code']);
        $descriptionParts = [
            'Booking kode: ' . ($booking['booking_code'] ?? '-'),
            'Layanan: ' . ($booking['service_name'] ?? '-'),
            'Status booking: ' . ($booking['booking_status'] ?? '-'),
            'Status pembayaran: ' . ($booking['payment_status'] ?? '-'),
            'Metode pengiriman: ' . ($booking['delivery_type'] ?? '-'),
            'Catatan: ' . trim((string) ($booking['notes'] ?? '-')),
        ];
        if (!empty($booking['destination_address'])) {
            $descriptionParts[] = 'Alamat: ' . $booking['destination_address'];
        }
        $description = $this->escapeIcsValue(implode("\n", $descriptionParts));
        $location = $this->escapeIcsValue($booking['destination_address'] ?? 'Laundry outlet');
        $times = explode('-', $booking['time_slot'] ?? '09:00-11:00');
        $startTime = trim($times[0]) ?: '09:00';
        $endTime = trim($times[1]) ?: date('H:i', strtotime($startTime . ' +2 hours'));
        $start = date('Ymd\THis', strtotime($booking['date'] . ' ' . $startTime . ':00'));
        $end = date('Ymd\THis', strtotime($booking['date'] . ' ' . $endTime . ':00'));
        $dtstamp = gmdate('Ymd\THis\Z');
        $status = match ((string) ($booking['booking_status'] ?? 'pending')) {
            'cancelled', 'rejected' => 'CANCELLED',
            'pending' => 'TENTATIVE',
            default => 'CONFIRMED',
        };
        $locationLine = 'LOCATION:' . $location;
        $url = base_url('/my-bookings');

        $ics = implode("\r\n", [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Laundry Booking//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:' . $uid,
            'DTSTAMP:' . $dtstamp,
            'SUMMARY:' . $summary,
            'DESCRIPTION:' . $description,
            'DTSTART:' . $start,
            'DTEND:' . $end,
            'STATUS:' . $status,
            $locationLine,
            'URL:' . $url,
            'TRANSP:OPAQUE',
            'END:VEVENT',
            'END:VCALENDAR',
            '',
        ]);

        return $this->response
            ->setHeader('Content-Type', 'text/calendar; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="booking-' . $booking['booking_code'] . '.ics"')
            ->setBody($ics);
    }
}
