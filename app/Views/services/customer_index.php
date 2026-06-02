<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #eef2f7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { background: linear-gradient(135deg, #2563eb 0%, #0d4d9d 100%); min-height: 100vh; color: white; position: fixed; left: 0; top: 0; width: 240px; padding-top: 22px; box-shadow: 2px 0 12px rgba(0,0,0,0.08); }
        .sidebar-brand { padding: 22px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.16); margin-bottom: 20px; font-size: 22px; font-weight: 700; }
        .sidebar-nav { list-style: none; }
        .sidebar-nav a { display: block; padding: 14px 20px; color: rgba(255,255,255,0.9); text-decoration: none; transition: all 0.2s ease; border-left: 4px solid transparent; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(255,255,255,0.1); color: white; border-left-color: white; }
        .sidebar-nav .nav-section { padding: 14px 20px 5px; font-size: 12px; color: rgba(255,255,255,0.7); text-transform: uppercase; font-weight: 700; margin-top: 16px; }
        main { margin-left: 240px; padding: 24px; }
        .header { background: white; border-radius: 14px; padding: 24px; box-shadow: 0 2px 18px rgba(0,0,0,0.05); margin-bottom: 24px; }
        .header h1 { font-size: 28px; color: #1f2937; margin-bottom: 8px; }
        .header p { color: #6b7280; margin: 0; }
        .service-card { background: white; border-radius: 18px; padding: 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; height: 100%; }
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
        <li><a href="<?= base_url('/customer/dashboard') ?>"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="<?= base_url('/booking') ?>"><i class="fas fa-shopping-bag"></i> Booking</a></li>
        <li><a href="<?= base_url('/my-bookings') ?>"><i class="fas fa-list"></i> My Bookings</a></li>
        <li><a href="<?= base_url('/services') ?>" class="active"><i class="fas fa-concierge-bell"></i> Services</a></li>
        <li class="nav-section">Akun</li>
        <li><a href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<main>
    <div class="header">
        <h1>Layanan Laundry</h1>
        <p>Lihat daftar layanan dan pilih yang paling sesuai dengan kebutuhan Anda.</p>
    </div>

    <div class="row g-4">
        <?php foreach ($services as $service): ?>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <?php if (!empty($service['photo']) && file_exists('uploads/services/' . $service['photo'])): ?>
                        <img src="<?= base_url('uploads/services/' . $service['photo']) ?>" class="img-fluid rounded mb-3" alt="<?= esc($service['name']) ?>" style="height:180px;width:100%;object-fit:cover;">
                    <?php endif; ?>
                    <h5 class="fw-bold mb-2"><?= esc($service['name']) ?></h5>
                    <p class="text-muted mb-3"><?= esc($service['description']) ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Rp <?= number_format($service['price'], 0, ',', '.') ?> / kg</span>
                        <a href="<?= base_url('/booking') ?>?service_id=<?= esc($service['id']) ?>" class="btn btn-sm btn-primary">Booking</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
