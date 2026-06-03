<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booking Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { background: linear-gradient(135deg, #32a852 0%, #1d7b3b 100%); min-height: 100vh; color: white; position: fixed; left: 0; top: 0; width: 240px; padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar-brand { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.15); margin-bottom: 20px; font-size: 22px; font-weight: bold; }
        .sidebar-nav { list-style: none; }
        .sidebar-nav a { display: block; padding: 15px 20px; color: rgba(255,255,255,0.85); text-decoration: none; transition: 0.3s; border-left: 4px solid transparent; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(255,255,255,0.12); color: white; border-left-color: white; }
        .sidebar-nav .nav-section { padding: 15px 20px 5px; font-size: 12px; color: rgba(255,255,255,0.65); text-transform: uppercase; font-weight: bold; margin-top: 15px; }
        main { margin-left: 240px; padding: 24px; }
        .header { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 28px; color: #333; margin-bottom: 4px; }
        .header p { color: #666; margin: 0; }
        .table-responsive { background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); overflow: hidden; }
        .table th, .table td { padding: 16px; vertical-align: middle; }
        .table thead { background: #f8f9fa; }
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
        <li><a href="<?= base_url('/staff/dashboard') ?>"><i class="fas fa-chart-simple"></i> Dashboard</a></li>
        <li><a href="<?= base_url('/staff/bookings') ?>" class="active"><i class="fas fa-calendar-check"></i> Daftar Booking</a></li>
        <li class="nav-section">Lainnya</li>
        <li><a href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<main>
    <div class="header">
        <div>
            <h1>Daftar Booking</h1>
            <p>Kelola status booking dan pembayaran pelanggan.</p>
        </div>
        <a href="<?= base_url('/staff/dashboard') ?>" class="btn btn-outline-primary">Kembali ke Dashboard</a>
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

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="p-3 bg-white rounded shadow-sm">
                <h2 class="h6">Tugas Harian Saya</h2>
                <?php if (empty($dailyTasks)) : ?>
                    <p class="text-muted mb-0">Belum ada tugas harian.</p>
                <?php else : ?>
                    <ul class="mb-0">
                        <?php foreach ($dailyTasks as $task): ?>
                            <li>
                                <?= esc($task['booking_code']) ?> - <?= esc($task['service_name']) ?> (<?= esc($task['time_slot']) ?>)
                                <?php if (!empty($task['notes'])): ?>
                                    <br><small class="text-muted">Catatan: <?= esc(mb_strimwidth((string) $task['notes'], 0, 70, '...')) ?></small>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-3 bg-white rounded shadow-sm">
                <h2 class="h6">Riwayat Pekerjaan Pribadi</h2>
                <?php if (empty($personalHistory)) : ?>
                    <p class="text-muted mb-0">Belum ada riwayat.</p>
                <?php else : ?>
                    <ul class="mb-0">
                        <?php foreach ($personalHistory as $history): ?>
                            <li><?= esc($history['booking_code']) ?> - <?= esc($history['booking_status']) ?> (<?= esc($history['date']) ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Harga</th>
                    <th>Catatan Laundry</th>
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
                            <td>
                                <?php if (!empty($booking['notes'])): ?>
                                    <small class="text-dark d-block"><?= esc(mb_strimwidth((string) $booking['notes'], 0, 80, '...')) ?></small>
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada catatan</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-<?= ($booking['payment_status'] ?? 'unpaid') == 'paid' ? 'success' : (($booking['payment_status'] ?? '') == 'failed' ? 'danger' : 'warning') ?>"><?= esc($booking['payment_status'] ?? 'unpaid') ?></span></td>
                            <td><span class="badge bg-<?= ($booking['booking_status'] ?? 'pending') == 'completed' ? 'success' : (($booking['booking_status'] ?? '') == 'rejected' ? 'danger' : 'info') ?>"><?= esc($booking['booking_status'] ?? 'pending') ?></span></td>
                            <td>
                                <form method="post" action="<?= base_url('/staff/bookings/update-status/' . ($booking['id'] ?? 0)) ?>" class="status-form" enctype="multipart/form-data">
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
                                    <input type="file" name="work_proof_photo" class="form-control form-control-sm" accept="image/*">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                                <?php if (!empty($booking['work_proof_photo'])): ?>
                                    <a href="<?= base_url('uploads/work-proofs/' . $booking['work_proof_photo']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-1">Lihat Bukti</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada booking untuk ditampilkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($pager)) : ?>
        <div class="mt-3"><?= $pager->links() ?></div>
    <?php endif; ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
