<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking Saya</title>
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
        .content-card { background: white; border-radius: 16px; box-shadow: 0 2px 18px rgba(0,0,0,0.05); padding: 24px; }
        .table-responsive { border-radius: 12px; }

        @media (max-width: 768px) {
            main { margin-left: 0; }
            .sidebar { position: relative; width: 100%; min-height: auto; }
        }
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
        <li><a href="<?= base_url('/my-bookings') ?>" class="active"><i class="fas fa-list"></i> My Bookings</a></li>
        <li><a href="<?= base_url('/services') ?>"><i class="fas fa-concierge-bell"></i> Services</a></li>
        <li class="nav-section">Akun</li>
        <li><a href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<main>
    <div class="content-card">
        <h1 class="mb-4">Riwayat Booking Saya</h1>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (empty($bookings)) : ?>
            <div class="alert alert-info">Belum ada booking.</div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Servis</th>
                            <th>Jadwal</th>
                            <th>Tujuan</th>
                            <th>Total</th>
                            <th>Status Booking</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking) : ?>
                            <tr>
                                <td><?= esc($booking['booking_code']) ?></td>
                                <td><?= esc($booking['service_name']) ?></td>
                                <td><?= esc($booking['date']) ?> <?= esc($booking['time_slot']) ?></td>
                                <td>
                                    <?php if (($booking['delivery_type'] ?? 'pickup') === 'delivery') : ?>
                                        <strong><?= esc($booking['destination_province_name'] ?? '-') ?></strong><br>
                                        <?= esc($booking['destination_city_name'] ?? '-') ?><br>
                                        <small class="text-muted"><?= esc($booking['destination_address'] ?? '-') ?></small>
                                    <?php else : ?>
                                        <span class="badge bg-secondary">Ambil sendiri</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                                    <?php if (!empty($booking['shipping_cost']) && $booking['shipping_cost'] > 0) : ?>
                                        <br><small class="text-muted">Ongkir: Rp <?= number_format($booking['shipping_cost'], 0, ',', '.') ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $booking['booking_status'] === 'confirmed' ? 'success' : ($booking['booking_status'] === 'rejected' ? 'danger' : 'warning') ?>">
                                        <?= esc($booking['booking_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $booking['payment_status'] === 'paid' ? 'success' : ($booking['payment_status'] === 'failed' ? 'danger' : 'warning') ?>">
                                        <?= esc($booking['payment_status']) ?>
                                    </span>
                                </td>
                            <td>
                                <?php if ($booking['payment_status'] !== 'paid') : ?>
                                    <a href="<?= base_url('/payment/checkout/' . $booking['id']) ?>" class="btn btn-primary btn-sm mb-1">Bayar</a>
                                <?php endif; ?>
                                <?php if ($booking['booking_status'] === 'pending') : ?>
                                    <form action="<?= base_url('/my-bookings/cancel/' . $booking['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm mb-1" onclick="return confirm('Batalkan booking ini?')">Batalkan</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($booking['booking_status'] === 'confirmed') : ?>
                                    <?php if (empty($booking['google_event_id'])) : ?>
                                        <a href="<?= base_url('/calendar/sync/' . $booking['id']) ?>" class="btn btn-success btn-sm">Sinkron Google Calendar</a>
                                        <?php else : ?>
                                            <span class="badge bg-success">Tersinkron</span>
                                        <?php endif; ?>
                                <?php endif; ?>
                                <a href="<?= base_url('/calendar/download/' . $booking['id']) ?>" class="btn btn-outline-secondary btn-sm mt-1">Download ICS</a>
                                <?php if ($booking['booking_status'] === 'completed') : ?>
                                    <?php if (empty($booking['review_id'])) : ?>
                                        <form action="<?= base_url('/my-bookings/review/' . $booking['id']) ?>" method="post" class="mt-2">
                                            <?= csrf_field() ?>
                                            <select name="rating" class="form-select form-select-sm mb-1" required>
                                                <option value="">Rating</option>
                                                <option value="5">5 - Sangat puas</option>
                                                <option value="4">4 - Puas</option>
                                                <option value="3">3 - Cukup</option>
                                                <option value="2">2 - Kurang</option>
                                                <option value="1">1 - Buruk</option>
                                            </select>
                                            <textarea name="review" class="form-control form-control-sm mb-1" placeholder="Tulis ulasan singkat (opsional)"></textarea>
                                            <button class="btn btn-success btn-sm">Kirim Ulasan</button>
                                        </form>
                                    <?php else : ?>
                                        <small class="d-block mt-1 text-success">Ulasan: <?= esc($booking['rating']) ?>/5</small>
                                        <?php if (!empty($booking['review'])) : ?>
                                            <small class="d-block text-muted"><?= esc($booking['review']) ?></small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($pager)) : ?>
            <div class="mt-3"><?= $pager->links() ?></div>
        <?php endif; ?>
    <?php endif; ?>
    </div>
</main>

</body>
</html>
