<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: white; position: fixed; left: 0; top: 0; width: 240px; padding-top: 22px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar-brand { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.14); margin-bottom: 20px; font-size: 22px; font-weight: bold; }
        .sidebar-nav { list-style: none; padding: 0; margin: 0; }
        .sidebar-nav a { display: block; padding: 14px 20px; color: rgba(255,255,255,0.9); text-decoration: none; transition: all 0.2s ease; border-left: 4px solid transparent; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(255,255,255,0.12); color: white; border-left-color: white; }
        .sidebar-nav .nav-section { padding: 14px 20px 6px; font-size: 12px; color: rgba(255,255,255,0.7); text-transform: uppercase; font-weight: bold; margin-top: 16px; }
        main { margin-left: 240px; padding: 24px; }
        .page-header { background: white; border-radius: 14px; padding: 24px; box-shadow: 0 2px 18px rgba(0,0,0,0.05); margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
        .page-header h1 { margin: 0; font-size: 28px; }
        .page-header p { margin: 0; color: #6b7280; }
        .card-panel { background: white; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.05); padding: 24px; margin-bottom: 24px; }
        .table-responsive { background: white; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.05); padding: 18px; }
        .nowrap { white-space: nowrap; }
        .table th, .table td { vertical-align: middle; }
        @media (max-width: 768px) { main { margin-left: 0; } .sidebar { position: relative; width: 100%; min-height: auto; } }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand"><i class="fas fa-soap"></i> Laundry Admin</div>
    <ul class="sidebar-nav">
        <li class="nav-section">Menu</li>
        <li><a href="<?= base_url('/admin/dashboard') ?>"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="<?= base_url('/admin/bookings') ?>"><i class="fas fa-calendar-check"></i> Bookings</a></li>
        <li><a href="<?= base_url('/admin/services') ?>"><i class="fas fa-concierge-bell"></i> Services</a></li>
        <li><a href="<?= base_url('/admin/schedules') ?>" class="active"><i class="fas fa-clock"></i> Schedules</a></li>
        <li><a href="<?= base_url('/admin/users') ?>"><i class="fas fa-users"></i> Users</a></li>
        <li class="nav-section">Lainnya</li>
        <li><a href="<?= base_url('/admin/notifications') ?>"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>
<main>
    <div class="page-header">
        <div>
            <h1>Kelola Jadwal</h1>
            <p>Daftar jadwal layanan dan kapasitas yang tersedia.</p>
        </div>
        <a href="<?= base_url('/admin/schedules/create') ?>" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Tambah Jadwal</a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Layanan</th>
                    <th>Tanggal</th>
                    <th>Slot Waktu</th>
                    <th>Kap.</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($schedules)) : ?>
                    <?php foreach ($schedules as $s) : ?>
                        <tr>
                            <td><?= esc($s['id']) ?></td>
                            <td><?= esc($s['service_name'] ?? '') ?></td>
                            <td><?= esc($s['date']) ?></td>
                            <td class="nowrap"><?= esc($s['time_slot']) ?></td>
                            <td><?= esc($s['capacity']) ?></td>
                            <td>
                                <a href="<?= base_url('/admin/schedules/edit/' . $s['id']) ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                <a href="<?= base_url('/admin/schedules/delete/' . $s['id']) ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Hapus jadwal ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="6" class="text-center">Belum ada jadwal.</td></tr>
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