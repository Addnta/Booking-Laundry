<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title">Checkout Pembayaran</h1>
            <p class="text-muted">Booking kode: <strong><?= esc($booking['booking_code']) ?></strong></p>

            <div class="mb-3">
                <strong>Servis:</strong> <?= esc($booking['service_name'] ?? $booking['service_id']) ?>
            </div>
            <div class="mb-3">
                <strong>Total:</strong> Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
            </div>
            <div class="mb-3">
                <strong>Status pembayaran:</strong>
                <span class="badge bg-<?= $booking['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                    <?= esc($booking['payment_status']) ?>
                </span>
            </div>

            <?php if (empty($snapToken)) : ?>
                <div class="alert alert-danger">
                    Tidak dapat memproses pembayaran saat ini. Silakan hubungi admin.
                </div>
            <?php else : ?>
                <button id="payButton" class="btn btn-primary">Bayar Sekarang</button>
                <div id="paymentResult" class="mt-3"></div>
            <?php endif; ?>

            <a href="<?= base_url('/my-bookings') ?>" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>

<?php if (!empty($snapToken)) : ?>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= esc($clientKey) ?>"></script>
<script>
    const payButton = document.getElementById('payButton');
    const resultContainer = document.getElementById('paymentResult');

    function updatePayment(result) {
        resultContainer.innerHTML = '<div class="alert alert-info">Memproses status pembayaran...</div>';

        fetch('<?= base_url('/api/payment/callback') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(result)
        })
            .then(response => response.json())
            .then(data => {
                resultContainer.innerHTML = '<div class="alert alert-success">Pembayaran diproses. Anda akan diarahkan kembali.</div>';
                setTimeout(() => {
                    window.location.href = '<?= base_url('/my-bookings') ?>';
                }, 1800);
            })
            .catch(error => {
                resultContainer.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat memperbarui status pembayaran.</div>';
                console.error(error);
            });
    }

    payButton.addEventListener('click', function () {
        window.snap.pay('<?= esc($snapToken) ?>', {
            onSuccess: updatePayment,
            onPending: updatePayment,
            onError: updatePayment,
            onClose: function () {
                resultContainer.innerHTML = '<div class="alert alert-warning">Pembayaran dibatalkan atau ditutup.</div>';
            }
        });
    });
</script>
<?php endif; ?>

</body>
</html>
