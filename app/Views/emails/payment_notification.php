<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2><?= esc($title) ?></h2>
    <p>Halo <?= esc($user['name'] ?? 'Customer') ?>,</p>
    <p><?= esc($message) ?></p>

    <?php if (!empty($bookingCode)) : ?>
        <p>Kode booking: <strong><?= esc($bookingCode) ?></strong></p>
    <?php endif; ?>

    <p>Terima kasih telah menggunakan layanan laundry online kami.</p>
    <p>Salam,<br>Laundry Booking System</p>
</body>
</html>
