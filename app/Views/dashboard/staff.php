<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        .sidebar {
            background: linear-gradient(135deg, #32a852 0%, #1d7b3b 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: bold;
        }

        .sidebar-nav { list-style: none; }
        .sidebar-nav a {
            display: block;
            padding: 15px 20px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255,255,255,0.12);
            color: white;
            border-left-color: white;
        }

        .sidebar-nav .nav-section {
            padding: 15px 20px 5px;
            font-size: 12px;
            color: rgba(255,255,255,0.65);
            text-transform: uppercase;
            font-weight: bold;
            margin-top: 15px;
        }

        main { margin-left: 240px; padding: 24px; }
        .header { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .header-title h1 { font-size: 28px; color: #333; margin-bottom: 6px; }
        .header-title p { color: #666; margin-bottom: 0; }
        .header-actions .btn { min-width: 150px; }

        .stat-card { background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 24px; border-left: 5px solid; transition: transform 0.2s ease; }
        .stat-card:hover { transform: translateY(-4px); }
        .stat-card.blue { border-color: #0d6efd; }
        .stat-card.green { border-color: #198754; }
        .stat-card.orange { border-color: #fd7e14; }
        .stat-card.red { border-color: #dc3545; }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; }
        .stat-card.blue .stat-icon { background: rgba(13,110,253,0.12); color: #0d6efd; }
        .stat-card.green .stat-icon { background: rgba(25,135,84,0.12); color: #198754; }
        .stat-card.orange .stat-icon { background: rgba(253,126,20,0.12); color: #fd7e14; }
        .stat-card.red .stat-icon { background: rgba(220,53,69,0.12); color: #dc3545; }
        .stat-value { font-size: 32px; font-weight: 700; color: #222; margin-bottom: 6px; }
        .stat-label { color: #777; }

        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap: 18px; margin-bottom: 32px; }
        .quick-btn { background: white; border-radius: 16px; padding: 24px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.06); text-decoration: none; color: #333; border: 1px solid transparent; transition: all 0.2s ease; }
        .quick-btn:hover { transform: translateY(-3px); border-color: #e3e9f3; }
        .quick-btn i { font-size: 28px; margin-bottom: 12px; display: block; }
        .quick-btn span { display: block; font-weight: 700; margin-bottom: 6px; }
        .quick-btn small { color: #777; }

        .table-responsive { background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); overflow: hidden; }
        .table th, .table td { padding: 16px; vertical-align: middle; }
        .table thead { background: #f8f9fa; }
        .table th { border-bottom: none; }

        .badge { border-radius: 999px; padding: 8px 14px; font-size: 0.8rem; }
        .status-form { display: flex; flex-wrap: wrap; gap: 8px; }

        @media (max-width: 768px) { main { margin-left: 0; } .sidebar { position: relative; width: 100%; min-height: auto; } }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-user-tie"></i> Staff Laundry
    </div>
    <ul class="sidebar-nav">
        <li class="nav-section">Menu</li>
        <li><a href="<?= base_url('/staff/dashboard') ?>" class="active"><i class="fas fa-chart-simple"></i> Dashboard</a></li>
        <li><a href="#summary"><i class="fas fa-chart-pie"></i> Ringkasan</a></li>
        <li><a href="<?= base_url('/staff/bookings') ?>"><i class="fas fa-calendar-check"></i> Daftar Booking</a></li>
        <li class="nav-section">Lainnya</li>
        <li><a href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<main>
    <div class="header">
        <div class="header-title">
            <h1>Dashboard Staff</h1>
            <p>Kelola booking pelanggan dan update status dengan cepat.</p>
        </div>
        <div class="header-actions">
            <a href="<?= base_url('/staff/bookings') ?>" class="btn btn-outline-primary">Lihat Booking</a>
        </div>
    </div>

    <div id="summary" class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-value"><?= $totalBookings ?? 0 ?></div>
                <div class="stat-label">Total Booking</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-value"><?= count(array_filter($bookings, fn($b) => ($b['payment_status'] ?? '') == 'paid')) ?></div>
                <div class="stat-label">Booking Lunas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-spinner"></i></div>
                <div class="stat-value"><?= count(array_filter($bookings, fn($b) => ($b['booking_status'] ?? '') == 'process')) ?></div>
                <div class="stat-label">Sedang Diproses</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card red">
                <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
                <div class="stat-value"><?= count(array_filter($bookings, fn($b) => ($b['booking_status'] ?? '') == 'pending')) ?></div>
                <div class="stat-label">Menunggu</div>
            </div>
        </div>
    </div>

    <div class="quick-actions mb-4">
        <a href="<?= base_url('/staff/bookings') ?>" class="quick-btn">
            <i class="fas fa-list-check"></i>
            <span>Booking</span>
            <small>Proses dan update status booking</small>
        </a>
        <a href="#summary" class="quick-btn">
            <i class="fas fa-chart-pie"></i>
            <span>Ringkasan</span>
            <small>Lihat statistik booking hari ini</small>
        </a>
        <a href="<?= base_url('/staff/dashboard') ?>" class="quick-btn">
            <i class="fas fa-rotate-right"></i>
            <span>Refresh</span>
            <small>Muat ulang data dashboard</small>
        </a>
        <a href="<?= base_url('/logout') ?>" class="quick-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
            <small>Keluar dari akun</small>
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div id="bookings" class="section-title">Daftar Booking Terbaru</div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Harga</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($bookings)): ?>
                    <?php foreach($bookings as $booking): ?>
                        <tr>
                            <td><strong><?= esc($booking['booking_code']) ?></strong></td>
                            <td><?= esc($booking['customer_name'] ?? '-') ?></td>
                            <td><?= esc($booking['service_name'] ?? '-') ?></td>
                            <td>Rp <?= number_format($booking['total_price'] ?? 0, 0, ',', '.') ?></td>
                            <td><span class="badge bg-<?= ($booking['payment_status'] ?? 'unpaid') == 'paid' ? 'success' : (($booking['payment_status'] ?? '') == 'failed' ? 'danger' : 'warning') ?>">
                                <?= esc($booking['payment_status'] ?? 'unpaid') ?></span></td>
                            <td><span class="badge bg-<?= ($booking['booking_status'] ?? 'pending') == 'completed' ? 'success' : (($booking['booking_status'] ?? '') == 'rejected' ? 'danger' : 'info') ?>">
                                <?= esc($booking['booking_status'] ?? 'pending') ?></span></td>
                            <td>
                                <form method="post" action="<?= base_url('/staff/bookings/update-status/' . ($booking['id'] ?? 0)) ?>" class="status-form">
                                    <?= csrf_field() ?>
                                    <select name="payment_status" class="form-select form-select-sm">
                                        <option value="">Payment</option>
                                        <option value="unpaid" <?= ($booking['payment_status'] ?? '') == 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                        <option value="paid" <?= ($booking['payment_status'] ?? '') == 'paid' ? 'selected' : '' ?>>Paid</option>
                                        <option value="failed" <?= ($booking['payment_status'] ?? '') == 'failed' ? 'selected' : '' ?>>Failed</option>
                                    </select>
                                    <select name="booking_status" class="form-select form-select-sm">
                                        <option value="">Status</option>
                                        <option value="pending" <?= ($booking['booking_status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="process" <?= ($booking['booking_status'] ?? '') == 'process' ? 'selected' : '' ?>>Process</option>
                                        <option value="completed" <?= ($booking['booking_status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada booking untuk ditampilkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
