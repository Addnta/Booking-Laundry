<?php

namespace App\Controllers;

use App\Models\ServiceModel;
use App\Models\ScheduleModel;
use App\Models\BookingModel;
use App\Models\ReviewModel;
use App\Libraries\NotificationService;
use App\Libraries\RajaOngkirService;
use App\Libraries\GoogleCalendarService;

class BookingController extends BaseController
{
    public function create()
    {
        // cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        // cek role customer
        if (session()->get('role') != 'customer') {
            return redirect()->back()
                ->with('error', 'Hanya customer yang dapat booking laundry');
        }

        $serviceModel = new ServiceModel();
        $scheduleModel = new ScheduleModel();
        $bookingModel = new BookingModel();
        $rajaOngkir = new RajaOngkirService();

        $data['services'] = $serviceModel->findAll();
        $data['schedules'] = $scheduleModel
            ->select('schedules.*, COUNT(bookings.id) as booked_count')
            ->join('bookings', 'bookings.schedule_id = schedules.id AND bookings.booking_status != "rejected"', 'left')
            ->groupBy('schedules.id')
            ->orderBy('schedules.date', 'ASC')
            ->findAll();
        foreach ($data['schedules'] as &$schedule) {
            $schedule['remaining_capacity'] = max(0, (int) $schedule['capacity'] - (int) $schedule['booked_count']);
        }
        unset($schedule);

        $data['provinces'] = $rajaOngkir->getProvinces();
        $data['apiKey'] = (string) env('api.key', '');

        return view(
            'bookings/index',
            $data
        );
    }

    public function store()
    {
        if (session()->get('role') != 'customer') {
            return redirect()->back()
                ->with('error', 'Akses ditolak');
        }

        $serviceModel = new ServiceModel();
        $scheduleModel = new ScheduleModel();
        $bookingModel = new BookingModel();

        $serviceId = (int) $this->request->getPost('service_id');
        $scheduleId = (int) $this->request->getPost('schedule_id');

        $service = $serviceModel->find($serviceId);

        if (!$service) {
            return redirect()->back()
                ->with('error', 'Layanan tidak ditemukan');
        }

        $schedule = $scheduleModel
            ->where('id', $scheduleId)
            ->where('service_id', $service['id'])
            ->first();

        if (!$schedule) {
            return redirect()->back()
                ->with('error', 'Jadwal tidak ditemukan atau tidak cocok dengan layanan');
        }

        $bookedCount = $bookingModel
            ->where('schedule_id', $schedule['id'])
            ->where('booking_status !=', 'rejected')
            ->countAllResults();

        if ($bookedCount >= $schedule['capacity']) {
            return redirect()->back()
                ->with('error', 'Maaf, jadwal ini sudah penuh. Silakan pilih jadwal lain.');
        }

        $weight = (float) str_replace(',', '.', $this->request->getPost('weight'));
        $deliveryType = $this->request->getPost('delivery_type') === 'delivery' ? 'delivery' : 'pickup';
        $destinationProvinceId = (int) $this->request->getPost('destination_province_id');
        $destinationCityId = (int) $this->request->getPost('destination_city_id');
        $destinationAddress = $this->request->getPost('destination_address');
        $destinationProvinceName = trim((string) $this->request->getPost('destination_province_name'));
        $destinationCityName = trim((string) $this->request->getPost('destination_city_name'));

        if ($weight <= 0) {
            return redirect()->back()
                ->with('error', 'Berat cucian harus lebih besar dari 0');
        }

        $shippingCost = 0.00;
        if ($deliveryType === 'delivery') {
            if (!$destinationProvinceId || !$destinationCityId || empty($destinationAddress)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Pengiriman membutuhkan provinsi, kota tujuan, dan alamat lengkap.');
            }

            $rajaOngkir = new RajaOngkirService();
            $costResults = $rajaOngkir->calculateCost($destinationCityId, $weight);
            $shippingCost = (float) ($costResults[0]['cost'] ?? 0);

            if ($shippingCost <= 0 && !empty($costResults[0]['costs'][0]['cost'][0]['value'])) {
                $shippingCost = (float) $costResults[0]['costs'][0]['cost'][0]['value'];
            }

            if ($shippingCost <= 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghitung ongkos kirim. Coba lagi atau pilih metode pickup.');
            }
        }

        $totalPrice = ($service['price'] * $weight) + $shippingCost;

        $bookingCode = 'BOOK-' . time();

        try {
            $bookingModel->save([
                'booking_code' => $bookingCode,
                'user_id' => session()->get('user_id'),
                'service_id' => $service['id'],
                'schedule_id' => $schedule['id'],
                'booking_status' => 'pending',
                'payment_status' => 'unpaid',
                'delivery_type' => $deliveryType,
                'destination_province_id' => $destinationProvinceId ?: null,
                'destination_province_name' => $destinationProvinceName ?: null,
                'weight' => number_format($weight, 2, '.', ''),
                'destination_city_id' => $destinationCityId ?: null,
                'destination_city_name' => $destinationCityName ?: null,
                'destination_address' => $destinationAddress,
                'shipping_cost' => number_format($shippingCost, 2, '.', ''),
                'notes' => $this->request->getPost('notes'),
                'total_price' => number_format($totalPrice, 2, '.', ''),
            ]);
        } catch (\Throwable $throwable) {
            log_message('error', 'Booking save failed: {message}', [
                'message' => $throwable->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Booking gagal disimpan. Silakan coba lagi.');
        }

        $notificationService = new NotificationService();
        $notificationService->notify(
            session()->get('user_id'),
            'booking',
            'Booking Baru Dibuat',
            'Booking Anda dengan kode ' . $bookingCode . ' telah dibuat dan menunggu pembayaran.'
        );
        $notificationService->notifyAdmins(
            'booking',
            'Booking Baru Masuk',
            'Booking baru dengan kode ' . $bookingCode . ' atas nama ' . session()->get('name') . ' telah dibuat.'
        );

        return redirect()->to('/customer/dashboard')
            ->with('success', 'Booking berhasil dibuat dan sudah masuk ke sistem.');
    }

    public function myBookings()
    {
        $bookingModel = new BookingModel();
        $reviewModel = new ReviewModel();

        $query = $bookingModel
            ->select('
                bookings.*,
                services.name as service_name,
                schedules.date,
                schedules.time_slot,
                reviews.id as review_id,
                reviews.rating,
                reviews.review
            ')
            ->join('services', 'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->join('reviews', 'reviews.booking_id = bookings.id', 'left')
            ->where('user_id', session()->get('user_id'))
            ->orderBy('bookings.created_at', 'DESC');

        $perPage = 10;
        $data['bookings'] = $query->paginate($perPage);
        $data['pager'] = $query->pager;

        return view('bookings/my_bookings', $data);
    }
    public function adminBookings()
    {
        $bookingModel = new \App\Models\BookingModel();
        $serviceModel = new ServiceModel();

        $statusFilter = $this->request->getGet('status');
        $dateFilter = $this->request->getGet('date');
        $serviceFilter = (int) $this->request->getGet('service_id');

        $query = $bookingModel
            ->select('bookings.*, users.name as customer_name, services.name as service_name, schedules.date as schedule_date, schedules.time_slot')
            ->join('users', 'users.id = bookings.user_id')
            ->join('services', 'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->orderBy('bookings.created_at', 'DESC');

        if (!empty($statusFilter)) {
            $query->where('bookings.booking_status', $statusFilter);
        }

        if (!empty($dateFilter)) {
            $query->where('schedules.date', $dateFilter);
        }

        if (!empty($serviceFilter)) {
            $query->where('bookings.service_id', $serviceFilter);
        }

        $perPage = 15;
        $data['bookings'] = $query->paginate($perPage);
        $data['pager'] = $query->pager;
        $data['services'] = $serviceModel->findAll();
        $data['filters'] = [
            'status' => $statusFilter,
            'date' => $dateFilter,
            'service_id' => $serviceFilter,
        ];

        return view('bookings/admin_index', $data);
    }

    public function edit($id)
    {
        $bookingModel = new \App\Models\BookingModel();
        $serviceModel = new \App\Models\ServiceModel();

        $data['booking'] = $bookingModel->find($id);
        if (!$data['booking']) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan');
        }

        $data['services'] = $serviceModel->findAll();

        return view('bookings/admin_edit', $data);
    }

    public function update($id)
    {
        $bookingModel = new \App\Models\BookingModel();
        $serviceModel = new \App\Models\ServiceModel();

        $booking = $bookingModel->find($id);
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan');
        }

        $service = $serviceModel->find($this->request->getPost('service_id'));
        if (!$service) {
            return redirect()->back()->with('error', 'Layanan tidak ditemukan');
        }

        $weight = (float) str_replace(',', '.', $this->request->getPost('weight'));
        if ($weight <= 0) {
            return redirect()->back()->with('error', 'Berat tidak valid');
        }

        $totalPrice = $service['price'] * $weight;

        $weight = (float) str_replace(',', '.', $this->request->getPost('weight'));

        if ($weight <= 0) {
            return redirect()->back()->with('error', 'Berat tidak valid');
        }

        $bookingModel->update($id, [
            'service_id' => $service['id'],
            'booking_status' => $this->request->getPost('booking_status'),
            'payment_status' => $this->request->getPost('payment_status'),
            'weight' => number_format($weight, 2, '.', ''),
            'notes' => $this->request->getPost('notes'),
            'total_price' => number_format($totalPrice, 2, '.', ''),
        ]);

        return redirect()->to('/admin/bookings')
            ->with('success', 'Booking berhasil diperbarui');
    }

    public function delete($id)
    {
        $bookingModel = new \App\Models\BookingModel();

        $booking = $bookingModel->find($id);
        if ($booking) {
            $bookingModel->delete($id);
        }

        return redirect()->to('/admin/bookings')
            ->with('success', 'Booking berhasil dihapus');
    }
    public function confirm($id)
    {
        $bookingModel = new \App\Models\BookingModel();

        $booking = $bookingModel->select('bookings.*, services.name as service_name, schedules.date, schedules.time_slot')
            ->join('services', 'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->where('bookings.id', $id)
            ->first();

        $bookingModel->update($id, [
            'booking_status' => 'confirmed'
        ]);

        $notificationService = new NotificationService();
        $notificationService->notify(
            $booking['user_id'],
            'booking',
            'Booking Dikonfirmasi',
            'Booking Anda dengan kode ' . $booking['booking_code'] . ' telah dikonfirmasi.'
        );

        $calendarService = new GoogleCalendarService();
        if ($calendarService->isConfigured() && empty($booking['google_event_id'])) {
            $eventId = $calendarService->createEvent($booking);
            if ($eventId) {
                $bookingModel->update($id, ['google_event_id' => $eventId]);
            }
        }

        return redirect()->back()
            ->with('success', 'Booking confirmed');
    }
    public function reject($id)
    {
        $bookingModel = new \App\Models\BookingModel();

        $bookingModel->update($id, [
            'booking_status' => 'rejected'
        ]);

        return redirect()->back()
            ->with('success', 'Booking rejected');
    }

    public function staffUpdateStatus($id)
    {
        if (session()->get('role') != 'staff') {
            return redirect()->back()
                ->with('error', 'Akses ditolak');
        }

        $bookingModel = new \App\Models\BookingModel();

        $booking = $bookingModel->find($id);
        if (!$booking) {
            return redirect()->back()
                ->with('error', 'Booking tidak ditemukan');
        }

        $paymentStatus = $this->request->getPost('payment_status');
        $bookingStatus = $this->request->getPost('booking_status');

        $update = ['assigned_staff_id' => session()->get('user_id')];
        if ($paymentStatus) {
            $update['payment_status'] = $paymentStatus;
        }
        if ($bookingStatus) {
            $update['booking_status'] = $bookingStatus;
        }

        $proofPhoto = $this->request->getFile('work_proof_photo');
        if ($proofPhoto && $proofPhoto->isValid() && !$proofPhoto->hasMoved()) {
            if (!is_dir('uploads/work-proofs')) {
                mkdir('uploads/work-proofs', 0775, true);
            }
            $proofName = $proofPhoto->getRandomName();
            $proofPhoto->move('uploads/work-proofs', $proofName);
            $update['work_proof_photo'] = $proofName;
        }

        if (!empty($update)) {
            $bookingModel->update($id, $update);
        }

        return redirect()->back()
            ->with('success', 'Status booking berhasil diperbarui');
    }

    public function staffBookings()
    {
        if (session()->get('role') != 'staff') {
            return redirect()->back()
                ->with('error', 'Akses ditolak');
        }

        $bookingModel = new BookingModel();

        $baseQuery = $bookingModel
            ->select('bookings.*, users.name as customer_name, services.name as service_name')
            ->join('users', 'users.id = bookings.user_id')
            ->join('services', 'services.id = bookings.service_id')
            ->orderBy('bookings.created_at', 'DESC');

        $perPage = 15;
        $data['bookings'] = $baseQuery->paginate($perPage);
        $data['pager'] = $baseQuery->pager;

        $today = date('Y-m-d');
        $staffId = (int) session()->get('user_id');
        $data['dailyTasks'] = $bookingModel
            ->select('bookings.*, services.name as service_name, schedules.date, schedules.time_slot')
            ->join('services', 'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->where('bookings.assigned_staff_id', $staffId)
            ->where('schedules.date', $today)
            ->whereIn('bookings.booking_status', ['confirmed', 'process'])
            ->orderBy('schedules.time_slot', 'ASC')
            ->findAll();

        $data['personalHistory'] = $bookingModel
            ->select('bookings.*, services.name as service_name, schedules.date, schedules.time_slot')
            ->join('services', 'services.id = bookings.service_id')
            ->join('schedules', 'schedules.id = bookings.schedule_id')
            ->where('bookings.assigned_staff_id', $staffId)
            ->whereIn('bookings.booking_status', ['completed', 'rejected', 'cancelled'])
            ->orderBy('bookings.updated_at', 'DESC')
            ->limit(20)
            ->findAll();

        return view('bookings/staff_index', $data);
    }

    public function cancel($id)
    {
        if (session()->get('role') != 'customer') {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($id);
        if (!$booking || (int) $booking['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan');
        }

        if ($booking['booking_status'] !== 'pending') {
            return redirect()->back()->with('error', 'Booking hanya bisa dibatalkan saat masih pending');
        }

        $bookingModel->update($id, ['booking_status' => 'cancelled']);
        return redirect()->back()->with('success', 'Booking berhasil dibatalkan');
    }

    public function submitReview($bookingId)
    {
        if (session()->get('role') != 'customer') {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $bookingModel = new BookingModel();
        $reviewModel = new ReviewModel();

        $booking = $bookingModel->find($bookingId);
        if (!$booking || (int) $booking['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan');
        }

        if ($booking['booking_status'] !== 'completed') {
            return redirect()->back()->with('error', 'Review hanya bisa diberikan setelah layanan selesai');
        }

        $exists = $reviewModel->where('booking_id', $bookingId)->first();
        if ($exists) {
            return redirect()->back()->with('error', 'Review untuk booking ini sudah ada');
        }

        $rating = (int) $this->request->getPost('rating');
        $review = trim((string) $this->request->getPost('review'));
        if ($rating < 1 || $rating > 5) {
            return redirect()->back()->with('error', 'Rating harus antara 1 sampai 5');
        }

        $reviewModel->save([
            'booking_id' => $bookingId,
            'rating' => $rating,
            'review' => $review ?: null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Terima kasih, ulasan berhasil dikirim');
    }
}
