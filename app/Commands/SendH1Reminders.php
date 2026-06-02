<?php

namespace App\Commands;

use App\Libraries\NotificationService;
use App\Models\BookingModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SendH1Reminders extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'reminder:h1';
    protected $description = 'Kirim reminder booking H-1 via email';

    public function run(array $params)
    {
        $bookingModel = new BookingModel();
        $notificationService = new NotificationService();
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $bookings = $bookingModel
            ->select('bookings.*, schedules.date, schedules.time_slot')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->where('schedules.date', $tomorrow)
            ->whereIn('bookings.booking_status', ['confirmed', 'process'])
            ->findAll();

        $count = 0;
        foreach ($bookings as $booking) {
            $sent = $notificationService->notify(
                $booking['user_id'],
                'reminder',
                'Reminder Booking H-1',
                'Besok Anda memiliki jadwal laundry pada ' . $booking['date'] . ' ' . $booking['time_slot'] . '.',
                $booking['booking_code']
            );
            if ($sent) {
                $count++;
            }
        }

        CLI::write('Reminder processed. Email sent: ' . $count, 'green');
    }
}
