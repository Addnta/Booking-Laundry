<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BookingModel;
use App\Models\ServiceModel;
use App\Models\NotificationModel;

class DashboardController extends BaseController
{
    public function admin()
    {
        $bookingModel = new BookingModel();
        $userModel = new UserModel();
        $serviceModel = new ServiceModel();
        $notificationModel = new NotificationModel();

        $data = [
            'totalUsers' => $userModel->countAll(),
            'totalBookings' => $bookingModel->countAll(),
            'totalServices' => $serviceModel->countAll(),
            'bookings' => $bookingModel
                ->select('bookings.*, users.name as customer_name, services.name as service_name')
                ->join('users', 'users.id = bookings.user_id')
                ->join('services', 'services.id = bookings.service_id')
                ->orderBy('bookings.created_at', 'DESC')
                ->limit(10)
                ->findAll(),
            'notifications' => $notificationModel
                ->where('user_id', session()->get('user_id'))
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll(),
            'unreadNotifications' => $notificationModel
                ->where('user_id', session()->get('user_id'))
                ->where('is_read', false)
                ->countAllResults(),
            'totalRevenue' => (float) ($bookingModel
                ->selectSum('total_price')
                ->where('payment_status', 'paid')
                ->first()['total_price'] ?? 0),
            'topServices' => $bookingModel
                ->select('services.name, COUNT(bookings.id) as total')
                ->join('services', 'services.id = bookings.service_id')
                ->groupBy('services.id')
                ->orderBy('total', 'DESC')
                ->limit(5)
                ->findAll(),
            'pendingBookings' => $bookingModel
                ->where('booking_status', 'pending')
                ->countAllResults(),
        ];

        return view('dashboard/admin', $data);
    }

    public function staff()
    {
        $bookingModel = new BookingModel();

        $data = [
            'totalBookings' => $bookingModel->countAll(),
            'bookings' => $bookingModel
                ->select('bookings.*, users.name as customer_name, services.name as service_name')
                ->join('users', 'users.id = bookings.user_id')
                ->join('services', 'services.id = bookings.service_id')
                ->orderBy('bookings.created_at', 'DESC')
                ->findAll(),
        ];

        return view('dashboard/staff', $data);
    }

    public function customer()
    {
        $bookingModel = new BookingModel();
        $serviceModel = new ServiceModel();

        $data = [
            'myBookings' => $bookingModel
                ->where('user_id', session()->get('user_id'))
                ->countAllResults(),
            'recentBookings' => $bookingModel
                ->select('bookings.*, services.name as service_name, schedules.date, schedules.time_slot')
                ->join('services', 'services.id = bookings.service_id')
                ->join('schedules', 'schedules.id = bookings.schedule_id')
                ->where('bookings.user_id', session()->get('user_id'))
                ->orderBy('bookings.created_at', 'DESC')
                ->limit(5)
                ->findAll(),
            'services' => $serviceModel->findAll(),
        ];

        return view('dashboard/customer', $data);
    }
}
