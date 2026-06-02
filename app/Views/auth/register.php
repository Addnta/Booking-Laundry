<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Poppins', sans-serif;
            height:100vh;
            background:
                linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?q=80&w=1470&auto=format&fit=crop');
            background-size:cover;
            background-position:center;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .register-card{
            width:100%;
            max-width:450px;
            background:white;
            padding:40px;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,0.3);
        }

        .title{
            color:#0d6efd;
            font-weight:700;
        }
    </style>
</head>
<body>

<div class="register-card">

    <h2 class="text-center title mb-4">
        Register Account
    </h2>

    <form action="<?= base_url('/register/process') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-4">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">
            Register
        </button>

    </form>

    <div class="text-center mt-3">
        <a href="<?= base_url('/login') ?>">
            Sudah punya akun?
        </a>
    </div>

</div>

</body>
</html>