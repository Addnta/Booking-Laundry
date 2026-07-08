<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-circle-check me-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-triangle-exclamation me-2"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-primary text-white"><i class="fas fa-concierge-bell"></i></div>
            <div class="stat-value"><?= esc($totalServices ?? 0) ?></div>
            <div class="stat-label">Total Services</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-success text-white"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-value"><?= esc($totalBookings ?? 0) ?></div>
            <div class="stat-label">Total Bookings</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-warning text-dark"><i class="fas fa-sack-dollar"></i></div>
            <div class="stat-value"><?= ui_money($totalRevenue ?? 0) ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-danger text-white"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-value"><?= esc($pendingBookings ?? 0) ?></div>
            <div class="stat-label">Pending Bookings</div>
        </div>
    </div>
</div>

<div class="section-title">Akses Cepat</div>
<div class="quick-grid">
    <a href="<?= base_url('/admin/services') ?>" class="quick-card">
        <i class="fas fa-concierge-bell"></i>
        <h5 class="fw-bold mb-2">Services</h5>
        <p class="text-muted mb-0">Kelola layanan, harga, dan detail produk dengan tampilan yang lebih bersih.</p>
    </a>
    <a href="<?= base_url('/admin/schedules') ?>" class="quick-card">
        <i class="fas fa-calendar-days"></i>
        <h5 class="fw-bold mb-2">Schedules</h5>
        <p class="text-muted mb-0">Atur jadwal dan kapasitas agar operasional tetap terkontrol.</p>
    </a>
    <a href="<?= base_url('/admin/bookings') ?>" class="quick-card">
        <i class="fas fa-list-check"></i>
        <h5 class="fw-bold mb-2">Bookings</h5>
        <p class="text-muted mb-0">Review booking terbaru dan tindak lanjuti lebih cepat.</p>
    </a>
    <a href="<?= base_url('/admin/users') ?>" class="quick-card">
        <i class="fas fa-users"></i>
        <h5 class="fw-bold mb-2">Users</h5>
        <p class="text-muted mb-0">Kelola akun admin, staff, dan customer dalam satu tempat.</p>
    </a>
</div>

<div class="section-title">Layanan Terpopuler</div>
<div class="table-shell mb-4">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Layanan</th>
                <th class="text-end">Total Booking</th>
            </tr>
        </thead>
        <tbody>
            <?php if (! empty($topServices)) : ?>
                <?php foreach ($topServices as $service) : ?>
                    <tr>
                        <td><?= esc($service['name'] ?? '-') ?></td>
                        <td class="text-end fw-semibold"><?= esc($service['total'] ?? 0) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="2" class="text-center py-4 text-muted">Belum ada data layanan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="section-title">Booking Terbaru</div>
<div class="table-shell">
    <table class="table align-middle table-hover">
        <thead>
            <tr>
                <th>Booking Code</th>
                <th>Customer</th>
                <th>Service</th>
                <th class="text-end">Harga</th>
                <th>Booking Status</th>
                <th>Payment Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (! empty($bookings)) : ?>
                <?php foreach ($bookings as $booking) : ?>
                    <tr>
                        <td class="fw-semibold"><?= esc($booking['booking_code'] ?? '-') ?></td>
                        <td><?= esc($booking['customer_name'] ?? '-') ?></td>
                        <td><?= esc($booking['service_name'] ?? '-') ?></td>
                        <td class="text-end"><?= ui_money($booking['total_price'] ?? 0) ?></td>
                        <td>
                            <span class="badge <?= ui_status_badge_class('booking', $booking['booking_status'] ?? 'pending') ?>">
                                <?= esc(ui_labelify($booking['booking_status'] ?? 'pending')) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= ui_status_badge_class('payment', $booking['payment_status'] ?? 'unpaid') ?>">
                                <?= esc(ui_labelify($booking['payment_status'] ?? 'unpaid')) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= base_url('/admin/bookings/edit/' . ($booking['id'] ?? 0)) ?>" class="btn btn-sm btn-primary mb-1">Edit</a>
                            <a href="<?= base_url('/admin/bookings/delete/' . ($booking['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-danger mb-1" onclick="return confirm('Hapus booking ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Tidak ada booking terbaru.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
