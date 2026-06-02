<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
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
        .panel { background: white; border-radius: 14px; padding: 20px; box-shadow: 0 2px 18px rgba(0,0,0,0.05); }
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
        <li><a href="<?= base_url('/admin/schedules') ?>"><i class="fas fa-clock"></i> Schedules</a></li>
        <li><a href="<?= base_url('/admin/users') ?>" class="active"><i class="fas fa-users"></i> Users</a></li>
        <li class="nav-section">Lainnya</li>
        <li><a href="<?= base_url('/admin/notifications') ?>"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<main>
    <div class="panel">
        <h1 class="h4 mb-3">Tambah User</h1>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= implode('<br>', session()->getFlashdata('errors')) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('/admin/users/store') ?>" class="bg-white p-3 rounded shadow-sm">
            <?= csrf_field() ?>
            <div class="mb-3"><label class="form-label">Nama</label><input class="form-control" name="name" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
            <div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
            <div class="mb-3"><label class="form-label">Phone</label><input class="form-control" name="phone"></div>
            <div class="mb-3"><label class="form-label">Role</label>
                <select class="form-select" name="role" required>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Status</label>
                <select class="form-select" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="<?= base_url('/admin/users') ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</main>
</body>
</html>
