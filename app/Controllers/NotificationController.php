<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Models\BookingModel;
use App\Libraries\NotificationService;

class NotificationController extends BaseController
{
    public function index()
    {
        $notificationModel = new NotificationModel();

        $data = array_merge($this->dashboardLayout->make('admin', [
            'pageKicker' => 'Administration alerts',
            'pageTitle' => 'Notifikasi Admin',
            'pageSubtitle' => 'Pantau pemberitahuan sistem dan tandai pesan yang sudah ditindaklanjuti.',
            'pageActions' => [
                [
                    'label' => 'Kembali ke Dashboard',
                    'href' => base_url('/admin/dashboard'),
                    'icon' => 'fa-arrow-left',
                    'class' => 'btn-outline-dark',
                ],
            ],
        ]), [
            'notifications' => $notificationModel
                ->where('user_id', session()->get('user_id'))
                ->orderBy('created_at', 'DESC')
                ->findAll(),
        ]);

        return view('dashboard/admin_notifications', $data);
    }

    public function markRead($id)
    {
        $notificationModel = new NotificationModel();
        $notification = $notificationModel->find($id);

        if ($notification && $notification['user_id'] === session()->get('user_id')) {
            $notificationModel->update($id, ['is_read' => true]);
        }

        return redirect()->back();
    }

    public function markAllRead()
    {
        $notificationModel = new NotificationModel();
        $notificationModel
            ->where('user_id', session()->get('user_id'))
            ->set(['is_read' => true])
            ->update();

        return redirect()->back();
    }

    public function sendH1Reminder()
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

        return redirect()->back()->with('success', 'Reminder H-1 diproses. Email terkirim: ' . $count);
    }
}
