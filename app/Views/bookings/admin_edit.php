<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Booking</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/admin/bookings/update/' . $booking['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Booking Code</label>
            <input type="text" class="form-control" value="<?= esc($booking['booking_code']) ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Customer</label>
            <input type="text" class="form-control" value="<?= esc($booking['user_id']) ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Pilih Servis</label>
            <select name="service_id" class="form-select" required>
                <?php foreach($services as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= $s['id'] == $booking['service_id'] ? 'selected' : '' ?>>
                        <?= esc($s['name']) ?> - Rp <?= number_format($s['price'],0,',','.') ?> / kg
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Berat (kg)</label>
            <input type="number" name="weight" class="form-control" step="0.1" min="0.1" value="<?= number_format($booking['weight'] ?? 1, 1, '.', '') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Booking Status</label>
            <select name="booking_status" class="form-select">
                <option value="pending" <?= $booking['booking_status']=='pending' ? 'selected' : '' ?>>pending</option>
                <option value="confirmed" <?= $booking['booking_status']=='confirmed' ? 'selected' : '' ?>>confirmed</option>
                <option value="rejected" <?= $booking['booking_status']=='rejected' ? 'selected' : '' ?>>rejected</option>
                <option value="process" <?= $booking['booking_status']=='process' ? 'selected' : '' ?>>process</option>
                <option value="completed" <?= $booking['booking_status']=='completed' ? 'selected' : '' ?>>completed</option>
                <option value="cancelled" <?= $booking['booking_status']=='cancelled' ? 'selected' : '' ?>>cancelled</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Status</label>
            <select name="payment_status" class="form-select">
                <option value="unpaid" <?= $booking['payment_status']=='unpaid' ? 'selected' : '' ?>>unpaid</option>
                <option value="paid" <?= $booking['payment_status']=='paid' ? 'selected' : '' ?>>paid</option>
                <option value="failed" <?= $booking['payment_status']=='failed' ? 'selected' : '' ?>>failed</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control"><?= esc($booking['notes']) ?></textarea>
        </div>

        <button class="btn btn-primary">Simpan Perubahan</button>
        <a href="<?= base_url('/admin/bookings') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>