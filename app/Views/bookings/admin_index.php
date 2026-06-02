<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bookings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            padding-top: 22px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.14);
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: bold;
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
            background: rgba(255,255,255,0.12);
            color: white;
            border-left-color: white;
        }

        .sidebar-nav .nav-section {
            padding: 14px 20px 6px;
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
            font-weight: bold;
            margin-top: 16px;
        }

        main { margin-left: 240px; padding: 24px; }
        .header { background: white; border-radius: 14px; padding: 24px; box-shadow: 0 2px 18px rgba(0,0,0,0.05); margin-bottom: 28px; display: flex; justify-content: space-between; align-items: center; }
        .header-title h1 { font-size: 28px; color: #1f2937; margin-bottom: 8px; }
        .header-title p { color: #6b7280; margin: 0; }
        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 18px; margin-bottom: 30px; }
        .quick-card { background: white; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.05); padding: 20px; text-align: center; text-decoration: none; color: #111827; border: 1px solid transparent; transition: all 0.2s ease; }
        .quick-card:hover { transform: translateY(-3px); border-color: #e5e7eb; }
        .quick-card i { font-size: 30px; margin-bottom: 12px; }
        .quick-card span { display: block; font-weight: 700; margin-bottom: 8px; }
        .quick-card small { color: #6b7280; }
        .filter-card, .table-responsive { background: white; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .filter-card .card-body { padding: 24px; }
        .table th, .table td { padding: 16px; vertical-align: middle; }
        .table thead { background: #f8f9fa; }
        .badge { padding: 8px 14px; border-radius: 999px; }
        @media (max-width: 768px) { main { margin-left: 0; } .sidebar { position: relative; width: 100%; min-height: auto; } }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-soap"></i> Laundry Admin
    </div>
    <ul class="sidebar-nav">
        <li class="nav-section">Menu</li>
        <li><a href="<?= base_url('/admin/dashboard') ?>"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="<?= base_url('/admin/bookings') ?>" class="active"><i class="fas fa-calendar-check"></i> Bookings</a></li>
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
            <h1>Bookings Admin</h1>
            <p>Kelola booking, konfirmasi, dan filter data dengan cepat.</p>
        </div>
        <div class="header-actions">
            <a href="<?= base_url('/admin/services') ?>" class="btn btn-outline-primary"><i class="fas fa-concierge-bell me-2"></i> Lihat Services</a>
        </div>
    </div>

    <div class="quick-actions">
        <a href="<?= base_url('/admin/bookings') ?>" class="quick-card">
            <i class="fas fa-list-check"></i>
            <span>Semua Booking</span>
            <small>Tampilkan daftar booking</small>
        </a>
        <a href="<?= base_url('/admin/services') ?>" class="quick-card">
            <i class="fas fa-concierge-bell"></i>
            <span>Services</span>
            <small>Kelola layanan</small>
        </a>
        <a href="<?= base_url('/admin/schedules') ?>" class="quick-card">
            <i class="fas fa-clock"></i>
            <span>Schedules</span>
            <small>Atur jadwal</small>
        </a>
        <a href="<?= base_url('/admin/users') ?>" class="quick-card">
            <i class="fas fa-users"></i>
            <span>Users</span>
            <small>Kelola user</small>
        </a>
    </div>

    <div class="filter-card">
        <div class="card-body">
            <form class="row g-3" method="get" action="<?= base_url('/admin/bookings') ?>">
                <div class="col-md-3">
                    <label class="form-label">Status Booking</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending" <?= isset($filters['status']) && $filters['status'] === 'pending' ? 'selected' : '' ?>>pending</option>
                        <option value="confirmed" <?= isset($filters['status']) && $filters['status'] === 'confirmed' ? 'selected' : '' ?>>confirmed</option>
                        <option value="rejected" <?= isset($filters['status']) && $filters['status'] === 'rejected' ? 'selected' : '' ?>>rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Jadwal</label>
                    <input type="date" name="date" class="form-control" value="<?= esc($filters['date'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Layanan</label>
                    <select name="service_id" class="form-select">
                        <option value="">Semua</option>
                        <?php foreach ($services as $service) : ?>
                            <option value="<?= $service['id'] ?>" <?= isset($filters['service_id']) && $filters['service_id'] == $service['id'] ? 'selected' : '' ?>><?= esc($service['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="<?= base_url('/admin/bookings') ?>" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)) : ?>
                    <?php foreach($bookings as $booking): ?>
                        <tr>
                            <td><?= esc($booking['booking_code']) ?></td>
                            <td><?= esc($booking['customer_name']) ?></td>
                            <td><?= esc($booking['service_name']) ?></td>
                            <td><?= esc($booking['schedule_date']) ?> <?= esc($booking['time_slot']) ?></td>
                            <td><span class="badge bg-<?= $booking['booking_status']=='confirmed' ? 'success' : ($booking['booking_status']=='rejected' ? 'danger' : 'warning') ?>"><?= esc($booking['booking_status']) ?></span></td>
                            <td><span class="badge bg-<?= $booking['payment_status']=='paid' ? 'success' : ($booking['payment_status']=='failed' ? 'danger' : 'secondary') ?>"><?= esc($booking['payment_status']) ?></span></td>
                            <td>
                                <a href="<?= base_url('/admin/bookings/edit/' . $booking['id']) ?>" class="btn btn-primary btn-sm mb-1">Edit</a>
                                <a href="<?= base_url('/admin/bookings/delete/' . $booking['id']) ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Hapus booking ini?')">Hapus</a>
                                <a href="<?= base_url('/admin/bookings/confirm/' . $booking['id']) ?>" class="btn btn-success btn-sm mb-1">Confirm</a>
                                <a href="<?= base_url('/admin/bookings/reject/' . $booking['id']) ?>" class="btn btn-warning btn-sm mb-1">Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">Belum ada booking.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pager)) : ?>
        <div class="mt-4">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
