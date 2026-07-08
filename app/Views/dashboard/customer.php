<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<?php $recentCompleted = count(array_filter($recentBookings ?? [], static fn (array $booking): bool => ($booking['booking_status'] ?? '') === 'completed')); ?>

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
            <div class="stat-icon bg-primary text-white"><i class="fas fa-concierge-bell"></i></div>
            <div class="stat-value"><?= esc($totalServices ?? count($services ?? [])) ?></div>
            <div class="stat-label">Layanan Tersedia</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-success text-white"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-value"><?= esc($myBookings ?? 0) ?></div>
            <div class="stat-label">Booking Saya</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-info text-white"><i class="fas fa-clock"></i></div>
            <div class="stat-value"><?= esc(count($recentBookings ?? [])) ?></div>
            <div class="stat-label">Booking Terbaru</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card dashboard-stat">
            <div class="stat-icon bg-warning text-dark"><i class="fas fa-award"></i></div>
            <div class="stat-value"><?= esc($recentCompleted) ?></div>
            <div class="stat-label">Siap Review</div>
        </div>
    </div>
</div>

<div class="section-title">Akses Cepat</div>
<div class="quick-grid">
    <a href="<?= base_url('/booking') ?>" class="quick-card">
        <i class="fas fa-bag-shopping"></i>
        <h5 class="fw-bold mb-2">Booking</h5>
        <p class="text-muted mb-0">Buat booking baru dengan alur yang singkat dan jelas.</p>
    </a>
    <a href="<?= base_url('/my-bookings') ?>" class="quick-card">
        <i class="fas fa-list"></i>
        <h5 class="fw-bold mb-2">My Bookings</h5>
        <p class="text-muted mb-0">Pantau status, pembayaran, dan progres layanan kamu.</p>
    </a>
    <a href="<?= base_url('/services') ?>" class="quick-card">
        <i class="fas fa-concierge-bell"></i>
        <h5 class="fw-bold mb-2">Services</h5>
        <p class="text-muted mb-0">Lihat layanan yang tersedia sebelum melakukan booking.</p>
    </a>
    <a href="<?= base_url('/logout') ?>" class="quick-card">
        <i class="fas fa-right-from-bracket"></i>
        <h5 class="fw-bold mb-2">Logout</h5>
        <p class="text-muted mb-0">Keluar dari akun dengan aman kapan saja.</p>
    </a>
</div>

<div class="section-title">Layanan Unggulan</div>
<div class="row g-4">
    <?php if (! empty($services)) : ?>
        <?php foreach ($services as $service) : ?>
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card h-100">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <h5 class="fw-bold mb-2"><?= esc($service['name'] ?? '-') ?></h5>
                            <p class="text-muted mb-0"><?= esc($service['description'] ?? '-') ?></p>
                        </div>
                        <div class="stat-icon bg-primary text-white mb-0">
                            <i class="fas fa-leaf"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><?= ui_money($service['price'] ?? 0) ?> / kg</span>
                        <a href="<?= base_url('/booking') ?>?service_id=<?= esc($service['id'] ?? 0) ?>" class="btn btn-sm btn-primary">Pilih</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12">
            <div class="info-panel p-4 text-center text-muted">
                Belum ada layanan yang ditampilkan.
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="section-title">Booking Terbaru Saya</div>
<div class="table-shell">
    <table class="table align-middle table-hover mb-0">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Layanan</th>
                <th>Jadwal</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th class="text-end">Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (! empty($recentBookings)) : ?>
                <?php foreach ($recentBookings as $booking) : ?>
                    <tr>
                        <td class="fw-semibold"><?= esc($booking['booking_code'] ?? '-') ?></td>
                        <td><?= esc($booking['service_name'] ?? '-') ?></td>
                        <td>
                            <?= esc($booking['date'] ?? '-') ?><br>
                            <small class="text-muted"><?= esc($booking['time_slot'] ?? '-') ?></small>
                        </td>
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
                        <td class="text-end"><?= ui_money($booking['total_price'] ?? 0) ?></td>
                        <td>
                            <a href="<?= base_url('/calendar/download/' . ($booking['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-secondary mb-1">ICS</a>
                            <?php if (($booking['booking_status'] ?? '') === 'confirmed') : ?>
                                <a href="<?= base_url('/calendar/sync/' . ($booking['id'] ?? 0)) ?>" class="btn btn-sm btn-success mb-1">Sync</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Belum ada booking. Silakan buat booking pertama kamu.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
