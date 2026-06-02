<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            z-index: 100;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .sidebar-nav {
            list-style: none;
        }

        .sidebar-nav li {
            padding: 0;
        }

        .sidebar-nav a {
            display: block;
            padding: 15px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }

        .sidebar-nav .nav-section {
            padding: 15px 20px 5px;
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            font-weight: bold;
            margin-top: 10px;
        }

        main {
            margin-left: 260px;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        .header-title p {
            color: #999;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            border-left: 4px solid;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-card.blue { border-left-color: #0d6efd; }
        .stat-card.green { border-left-color: #198754; }
        .stat-card.orange { border-left-color: #fd7e14; }
        .stat-card.red { border-left-color: #dc3545; }

        .stat-icon {
            font-size: 32px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .stat-card.blue .stat-icon { background: #e7f1ff; color: #0d6efd; }
        .stat-card.green .stat-icon { background: #e8f5e9; color: #198754; }
        .stat-card.orange .stat-icon { background: #fff3e0; color: #fd7e14; }
        .stat-card.red .stat-icon { background: #ffebee; color: #dc3545; }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #999;
            font-size: 14px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .quick-btn {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: 0.3s;
            border: 2px solid transparent;
        }

        .quick-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #667eea;
        }

        .quick-btn i {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
        }

        .quick-btn.services i { color: #667eea; }
        .quick-btn.schedules i { color: #764ba2; }
        .quick-btn.bookings i { color: #f093fb; }
        .quick-btn.users i { color: #4facfe; }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            margin-top: 30px;
        }

        .table-responsive {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table th {
            padding: 15px;
            font-weight: 600;
            color: #333;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            main {
                margin-left: 200px;
                padding: 15px;
            }

            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                transform: translateX(-100%);
                transition: 0.3s;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            main {
                margin-left: 0;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-soap"></i> Laundry Admin
    </div>
    <ul class="sidebar-nav">
        <li class="nav-section">Menu Utama</li>
        <li><a href="<?= base_url('/admin/dashboard') ?>" class="active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="<?= base_url('/admin/bookings') ?>"><i class="fas fa-calendar-check"></i> Bookings</a></li>
        
        <li class="nav-section">Management</li>
        <li><a href="<?= base_url('/admin/services') ?>"><i class="fas fa-concierge-bell"></i> Services</a></li>
        <li><a href="<?= base_url('/admin/schedules') ?>"><i class="fas fa-clock"></i> Schedules</a></li>
        <li><a href="<?= base_url('/admin/users') ?>"><i class="fas fa-users"></i> Users</a></li>
        
        <li class="nav-section">Lainnya</li>
        <li><a href="<?= base_url('/admin/notifications') ?>"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<main>
    <div class="header">
        <div class="header-title">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang kembali! Hari ini adalah <?= date('l, d M Y') ?></p>
        </div>
        <div class="header-actions">
            <a href="<?= base_url('/admin/services/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Service
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-concierge-bell"></i></div>
                <div class="stat-value"><?= $totalServices ?? 0 ?></div>
                <div class="stat-label">Total Services</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-value"><?= $totalBookings ?? 0 ?></div>
                <div class="stat-label">Total Bookings</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-value">Rp <?= number_format($totalRevenue ?? 0, 0, ',', '.') ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card red">
                <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                <div class="stat-value"><?= $pendingBookings ?? 0 ?></div>
                <div class="stat-label">Pending Bookings</div>
            </div>
        </div>
    </div>

    <div class="quick-actions">
        <a href="<?= base_url('/admin/services') ?>" class="quick-btn services">
            <i class="fas fa-concierge-bell"></i>
            <strong>Services</strong>
            <p style="font-size: 12px; color: #999; margin: 5px 0 0 0;">Kelola layanan</p>
        </a>
        <a href="<?= base_url('/admin/schedules') ?>" class="quick-btn schedules">
            <i class="fas fa-calendar-alt"></i>
            <strong>Schedules</strong>
            <p style="font-size: 12px; color: #999; margin: 5px 0 0 0;">Atur jadwal</p>
        </a>
        <a href="<?= base_url('/admin/bookings') ?>" class="quick-btn bookings">
            <i class="fas fa-list-check"></i>
            <strong>Bookings</strong>
            <p style="font-size: 12px; color: #999; margin: 5px 0 0 0;">Lihat booking</p>
        </a>
        <a href="<?= base_url('/admin/users') ?>" class="quick-btn users">
            <i class="fas fa-user-group"></i>
            <strong>Users</strong>
            <p style="font-size: 12px; color: #999; margin: 5px 0 0 0;">Kelola user</p>
        </a>
    </div>

    <div class="section-title">Layanan Terpopuler</div>
    <div class="table-responsive mb-4">
        <table class="table">
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Total Booking</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($topServices)) : ?>
                    <?php foreach ($topServices as $service) : ?>
                        <tr>
                            <td><?= esc($service['name']) ?></td>
                            <td><?= esc($service['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="2" class="text-center py-3">Belum ada data layanan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="section-title">Booking Terbaru</div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Booking Code</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Harga</th>
                        <th>Booking Status</th>
                        <th>Payment Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($bookings)): ?>
                        <?php foreach($bookings as $booking): ?>
                            <tr>
                                <td><strong><?= esc($booking['booking_code']) ?></strong></td>
                                <td><?= esc($booking['customer_name'] ?? '') ?></td>
                                <td><?= esc($booking['service_name'] ?? '') ?></td>
                                <td>Rp <?= number_format($booking['total_price'] ?? 0, 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-<?= ($booking['booking_status'] ?? 'pending') == 'confirmed' ? 'success' : (($booking['booking_status'] ?? '') == 'rejected' ? 'danger' : 'warning') ?>">
                                        <?= esc($booking['booking_status'] ?? 'pending') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= ($booking['payment_status'] ?? 'unpaid') == 'paid' ? 'success' : (($booking['payment_status'] ?? '') == 'failed' ? 'danger' : 'secondary') ?>">
                                        <?= esc($booking['payment_status'] ?? 'unpaid') ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('/admin/bookings/edit/' . ($booking['id'] ?? 0)) ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="<?= base_url('/admin/bookings/delete/' . ($booking['id'] ?? 0)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus booking ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-3">Tidak ada booking terbaru.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="<?= base_url('/admin/bookings') ?>" class="btn btn-outline-primary">Lihat Semua Booking</a>
        </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
