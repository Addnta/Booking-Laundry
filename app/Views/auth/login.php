<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Laundry Service</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Poppins', sans-serif;
            height:100vh;
            background:
                linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('https://images.unsplash.com/photo-1521656693074-0ef32e80a5d5?q=80&w=1470&auto=format&fit=crop');
            background-size:cover;
            background-position:center;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .login-card{
            width:100%;
            max-width:420px;
            background:white;
            padding:40px;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,0.3);
        }

        .btn-login{
            background:#0d6efd;
            border:none;
            border-radius:10px;
            padding:12px;
            font-weight:600;
        }

        .title{
            font-weight:700;
            color:#0d6efd;
        }
    </style>
</head>
<body>

<div class="login-card">

    <h2 class="text-center title mb-4">
        Laundry Service
    </h2>

    <?php if(session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/login/process') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label>Email</label>

            <input
                type="email"
                name="email"
                class="form-control"
                required
            >
        </div>

        <div class="mb-4">
            <label>Password</label>

            <input
                type="password"
                name="password"
                class="form-control"
                required
            >
        </div>

        <button class="btn btn-primary btn-login w-100">
            Login
        </button>

    </form>

    <div class="text-center mt-3">
        <a href="<?= base_url('/register') ?>">
            Belum punya akun?
        </a>
    </div>

</div>

</body>
</html>