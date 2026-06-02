<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Customer</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
            margin: 0;
        }

        .navbar {
            background: #4CAF50;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
        }

        .container {
            padding: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background: #f2f2f2;
        }

        a.logout {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
    </style>

</head>
<body>

<div class="navbar">
    <div>Dashboard Customer</div>
    <div>
        <a class="logout" href="/logout">Logout</a>
    </div>
</div>

<div class="container">

    <div class="card">

        <h2>Selamat Datang 👋</h2>

        <hr>

        <h3>Layanan Laundry</h3>

        <?php if (isset($services) && !empty($services)) : ?>

        

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">

    <?php foreach($services as $s): ?>

        <div style="background:white; padding:15px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); text-align:center;">

            <div style="font-size:40px;">🧺</div>

            <h3><?= $s['nama_service']; ?></h3>

            <p>Rp <?= number_format($s['harga']); ?></p>

            <a href="/booking/form/<?= $s['id_service']; ?>" 
   style="display:inline-block; margin-top:10px; padding:8px 12px; background:#4CAF50; color:white; text-decoration:none; border-radius:5px;">
   Pesan
</a>

        </div>

    <?php endforeach; ?>

</div>

        <?php else : ?>
            <p>Belum ada layanan tersedia</p>
        <?php endif; ?>

    </div>

</div>

</body>
</html>