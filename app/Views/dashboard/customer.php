<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #eef2f7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        .sidebar {
            background: linear-gradient(135deg, #2563eb 0%, #0d4d9d 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            padding-top: 22px;
            box-shadow: 2px 0 12px rgba(0,0,0,0.08);
        }

        .sidebar-brand {
            padding: 22px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.16);
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: 700;
        }

        .sidebar-nav { list-style: none; }
        .sidebar-nav a {
            display: block;
            padding: 14px 20px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }

        .sidebar-nav .nav-section {
            padding: 14px 20px 5px;
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
            font-weight: 700;
            margin-top: 16px;
        }

        main { margin-left: 240px; padding: 24px; }
        .header { background: white; border-radius: 14px; padding: 24px; box-shadow: 0 2px 18px rgba(0,0,0,0.05); margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center; }
        .header-title h1 { font-size: 28px; color: #1f2937; margin-bottom: 8px; }
        .header-title p { color: #6b7280; margin: 0; }

        .stat-card { background: white; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.05); padding: 24px; border-left: 5px solid; transition: transform 0.2s ease; }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-card.blue { border-color: #2563eb; }
        .stat-card.green { border-color: #16a34a; }
        .stat-card.cyan { border-color: #0ea5e9; }
        .stat-card.purple { border-color: #7c3aed; }
        .stat-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .stat-card.blue .stat-icon { background: rgba(37,99,235,0.12); color: #2563eb; }
        .stat-card.green .stat-icon { background: rgba(22,163,74,0.12); color: #16a34a; }
        .stat-card.cyan .stat-icon { background: rgba(14,165,233,0.12); color: #0ea5e9; }
        .stat-card.purple .stat-icon { background: rgba(124,58,237,0.12); color: #7c3aed; }
        .stat-value { font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .stat-label { color: #6b7280; }

        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap: 18px; margin-bottom: 32px; }
        .quick-btn { background: white; border-radius: 16px; padding: 22px; text-align: center; box-shadow: 0 2px 16px rgba(0,0,0,0.05); text-decoration: none; color: #111827; border: 1px solid transparent; transition: all 0.2s ease; }
        .quick-btn:hover { transform: translateY(-3px); border-color: #e5e7eb; }
        .quick-btn i { font-size: 30px; margin-bottom: 12px; display: block; }
        .quick-btn span { display: block; font-weight: 700; margin-bottom: 8px; }
        .quick-btn small { color: #6b7280; }

        .service-card { background: white; border-radius: 18px; padding: 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; }

        .table-responsive { background: white; border-radius: 16px; box-shadow: 0 2px 18px rgba(0,0,0,0.04); overflow: hidden; }
        .table th, .table td { padding: 16px; vertical-align: middle; }
        .table thead { background: #f8fafc; }
        .badge { padding: 8px 14px; border-radius: 999px; font-size: 0.8rem; }

        @media (max-width: 768px) { main { margin-left: 0; } .sidebar { position: relative; width: 100%; min-height: auto; } }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-user"></i> Customer
    </div>
    <ul class="sidebar-nav">
        <li class="nav-section">Menu</li>
        <li><a href="<?= base_url('/customer/dashboard') ?>" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="<?= base_url('/booking') ?>"><i class="fas fa-shopping-bag"></i> Booking</a></li>
        <li><a href="<?= base_url('/my-bookings') ?>"><i class="fas fa-list"></i> My Bookings</a></li>
        <li><a href="<?= base_url('/services') ?>"><i class="fas fa-concierge-bell"></i> Services</a></li>
        <li class="nav-section">Akun</li>
        <li><a href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<main>
    <div class="header">
        <div class="header-title">
            <h1>Dashboard Customer</h1>
            <p>Selamat datang! Pilih layanan dan booking laundry kamu dengan cepat.</p>
        </div>
        <div class="header-actions">
            <a href="<?= base_url('/booking') ?>" class="btn btn-primary">Booking Sekarang</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-concierge-bell"></i></div>
                <div class="stat-value"><?= count($services) ?? 0 ?></div>
                <div class="stat-label">Layanan Tersedia</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-value"><?= $myBookings ?? 0 ?></div>
                <div class="stat-label">Booking Saya</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card cyan">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-value"><?= count(array_filter($services, fn($s) => true)) ?></div>
                <div class="stat-label">Pilih Layanan</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card purple">
                <div class="stat-icon"><i class="fas fa-history"></i></div>
                <div class="stat-value"><?= $myBookings ?? 0 ?></div>
                <div class="stat-label">Riwayat Booking</div>
            </div>
        </div>
    </div>

    <div class="quick-actions">
        <a href="<?= base_url('/booking') ?>" class="quick-btn">
            <i class="fas fa-plus-circle"></i>
            <span>Booking</span>
            <small>Buat booking baru</small>
        </a>
        <a href="<?= base_url('/my-bookings') ?>" class="quick-btn">
            <i class="fas fa-list"></i>
            <span>My Bookings</span>
            <small>Cek status terbaru</small>
        </a>
        <a href="<?= base_url('/services') ?>" class="quick-btn">
            <i class="fas fa-concierge-bell"></i>
            <span>Services</span>
            <small>Lihat semua layanan</small>
        </a>
        <a href="<?= base_url('/logout') ?>" class="quick-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
            <small>Keluar dari akun</small>
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($services as $service): ?>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <h5 class="fw-bold mb-2"><?= esc($service['name']) ?></h5>
                    <p class="text-muted mb-3"><?= esc($service['description']) ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Rp <?= number_format($service['price'], 0, ',', '.') ?> / kg</span>
                        <a href="<?= base_url('/booking') ?>?service_id=<?= esc($service['id']) ?>" class="btn btn-sm btn-primary">Pilih</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Booking Terbaru Saya</h4>
            <a href="<?= base_url('/my-bookings') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Layanan</th>
                        <th>Jadwal</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentBookings)): ?>
                        <?php foreach ($recentBookings as $booking): ?>
                            <tr>
                                <td><strong><?= esc($booking['booking_code']) ?></strong></td>
                                <td><?= esc($booking['service_name'] ?? '-') ?></td>
                                <td>
                                    <?= esc($booking['date'] ?? '-') ?><br>
                                    <small class="text-muted"><?= esc($booking['time_slot'] ?? '-') ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-<?= ($booking['payment_status'] ?? 'unpaid') === 'paid' ? 'success' : (($booking['payment_status'] ?? '') === 'failed' ? 'danger' : 'warning') ?>">
                                        <?= esc($booking['payment_status'] ?? 'unpaid') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= ($booking['booking_status'] ?? 'pending') === 'completed' ? 'success' : (($booking['booking_status'] ?? '') === 'rejected' ? 'danger' : (($booking['booking_status'] ?? '') === 'cancelled' ? 'secondary' : 'info')) ?>">
                                        <?= esc($booking['booking_status'] ?? 'pending') ?>
                                    </span>
                                </td>
                                <td>Rp <?= number_format((float) ($booking['total_price'] ?? 0), 0, ',', '.') ?></td>
                                <td>
                                    <a href="<?= base_url('/calendar/download/' . $booking['id']) ?>" class="btn btn-sm btn-outline-secondary mb-1">ICS</a>
                                    <?php if (($booking['booking_status'] ?? '') === 'confirmed'): ?>
                                        <a href="<?= base_url('/calendar/sync/' . $booking['id']) ?>" class="btn btn-sm btn-success mb-1">Sync</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada booking. Silakan buat booking pertama kamu.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
