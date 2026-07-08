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

<div class="row g-4 mb-1">
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-primary text-white"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-value"><?= esc($totalBookings ?? 0) ?></div>
            <div class="stat-label">Total Booking</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-success text-white"><i class="fas fa-circle-dollar-to-slot"></i></div>
            <div class="stat-value"><?= esc($paidBookings ?? 0) ?></div>
            <div class="stat-label">Booking Lunas</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-warning text-dark"><i class="fas fa-spinner"></i></div>
            <div class="stat-value"><?= esc($processBookings ?? 0) ?></div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-danger text-white"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-value"><?= esc($pendingBookings ?? 0) ?></div>
            <div class="stat-label">Menunggu</div>
        </div>
    </div>
</div>

<div class="section-title">Akses Cepat</div>
<div class="quick-grid mb-4">
    <a href="<?= base_url('/staff/bookings') ?>" class="quick-card">
        <i class="fas fa-list-check"></i>
        <h5 class="fw-bold mb-2">Booking</h5>
        <p class="text-muted mb-0">Proses booking dan update status dengan cepat.</p>
    </a>
    <a href="#bookings" class="quick-card">
        <i class="fas fa-table"></i>
        <h5 class="fw-bold mb-2">Tabel</h5>
        <p class="text-muted mb-0">Langsung lompat ke daftar booking terbaru.</p>
    </a>
    <a href="<?= base_url('/staff/dashboard') ?>" class="quick-card">
        <i class="fas fa-rotate-right"></i>
        <h5 class="fw-bold mb-2">Refresh</h5>
        <p class="text-muted mb-0">Muat ulang data dashboard hari ini.</p>
    </a>
    <a href="<?= base_url('/logout') ?>" class="quick-card">
        <i class="fas fa-right-from-bracket"></i>
        <h5 class="fw-bold mb-2">Logout</h5>
        <p class="text-muted mb-0">Keluar dari akun staff dengan aman.</p>
    </a>
</div>

<div class="section-title" id="bookings">Daftar Booking Terbaru</div>
<div class="table-shell">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>Booking</th>
                <th>Customer</th>
                <th>Service</th>
                <th class="text-end">Harga</th>
                <th>Payment</th>
                <th>Status</th>
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
                            <span class="badge <?= ui_status_badge_class('payment', $booking['payment_status'] ?? 'unpaid') ?>">
                                <?= esc(ui_labelify($booking['payment_status'] ?? 'unpaid')) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= ui_status_badge_class('booking', $booking['booking_status'] ?? 'pending') ?>">
                                <?= esc(ui_labelify($booking['booking_status'] ?? 'pending')) ?>
                            </span>
                        </td>
                        <td>
                            <form method="post" action="<?= base_url('/staff/bookings/update-status/' . ($booking['id'] ?? 0)) ?>" class="d-grid gap-2">
                                <?= csrf_field() ?>
                                <select name="payment_status" class="form-select form-select-sm">
                                    <option value="">Payment</option>
                                    <option value="unpaid" <?= ($booking['payment_status'] ?? '') === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                    <option value="paid" <?= ($booking['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                    <option value="failed" <?= ($booking['payment_status'] ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                                </select>
                                <select name="booking_status" class="form-select form-select-sm">
                                    <option value="">Status</option>
                                    <option value="pending" <?= ($booking['booking_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="process" <?= ($booking['booking_status'] ?? '') === 'process' ? 'selected' : '' ?>>Process</option>
                                    <option value="completed" <?= ($booking['booking_status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Tidak ada booking untuk ditampilkan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
