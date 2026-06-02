<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .hero { background: linear-gradient(135deg, #0f172a, #1d4ed8); color: #fff; padding: 48px 0; }
        .endpoint { border-left: 4px solid #1d4ed8; }
        code { background: #e2e8f0; padding: 2px 6px; border-radius: 6px; }
    </style>
</head>
<body>
    <section class="hero mb-4">
        <div class="container">
            <h1 class="display-6 mb-2">Booking Service API</h1>
            <p class="mb-0">Dokumentasi endpoint REST, auth header, dan integrasi webservice.</p>
        </div>
    </section>

    <div class="container mb-5">
        <div class="card mb-4">
            <div class="card-body">
                <h5>Auth</h5>
                <p class="mb-2">Tambahkan header berikut pada request API:</p>
                <code><?= esc($authHeader) ?></code>
            </div>
        </div>

        <div class="row g-3">
            <?php foreach ($endpoints as $endpoint) : ?>
                <div class="col-12">
                    <div class="card endpoint">
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                <span class="badge bg-primary"><?= esc($endpoint['method']) ?></span>
                                <code><?= esc($endpoint['path']) ?></code>
                            </div>
                            <div><?= esc($endpoint['description']) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
