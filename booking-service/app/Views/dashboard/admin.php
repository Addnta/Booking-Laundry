<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>

  

    <style>
        body {
            font-family: Arial;
            margin: 0;
            background: #f4f6f8;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background: #2c3e50;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            color: white;
        }

        .sidebar h2 {
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 0;
        }

        .sidebar a:hover {
            background: #34495e;
            padding-left: 10px;
        }

        .main {
            margin-left: 240px;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .logout {
            color: #e74c3c;
        }
    </style>

</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>

    <a href="/admin/dashboard">Dashboard</a>
    <a href="#">Users</a>
    <a href="/admin/services">Services</a>
    <a href="#">Bookings</a>
    <a href="#">Payments</a>

    <a class="logout" href="/logout">Logout</a>
</div>

<div class="main">

    <div class="card">
        <h1>Dashboard Admin 👑</h1>
    </div>

    <hr>

<h3>Data Services</h3>

<a href="/admin/services/create"
   style="
        display:inline-block;
        padding:10px 15px;
        background:#4CAF50;
        color:white;
        text-decoration:none;
        border-radius:5px;
        margin-bottom:10px;
   ">
   + Tambah Service
</a>

<?php if(session()->getFlashdata('success')): ?>
    <div style="padding:10px; background:#d4edda; color:#155724; margin-bottom:10px;">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>

<div style="margin-top:20px;">
<table style="width:100%; border-collapse: collapse;" border="1">
    <tr>
        <th>Nama Service</th>
        <th>Harga</th>
        <th>Aksi</th>
    </tr>

    <?php foreach($services as $s): ?>
    <tr>
        <td><?= $s['nama_service']; ?></td>
        <td>Rp <?= number_format($s['harga']); ?></td>
        <td>
            <a href="/admin/services/edit/<?= $s['id_service']; ?>">Edit</a> |
            <a href="/admin/services/delete/<?= $s['id_service']; ?>"
               onclick="return confirm('Yakin hapus?')">
               Hapus
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>
<h3>Tambah Service</h3>

<form action="/admin/services/store" method="post">

    <input type="text" name="nama_service" placeholder="Nama Service"><br><br>

    <input type="number" name="harga" placeholder="Harga"><br><br>

    <button type="submit">Simpan</button>

</form>
</div>

</body>
</html>