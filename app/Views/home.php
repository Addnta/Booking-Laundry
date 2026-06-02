# Modern Home Page — `app/Views/home.php`

```php
<!DOCTYPE html>
<html>
<head>

    <title>Laundry Booking System</title>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <link href=
"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href=
"https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

    <style>

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fb;
        }

        .navbar {
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
        }

        .hero {
            height: 100vh;
            background:
                linear-gradient(
                    rgba(0,0,0,0.6),
                    rgba(0,0,0,0.6)
                ),
                url('https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?q=80&w=1400');

            background-size: cover;
            background-position: center;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;

            color: white;
        }

        .hero h1 {
            font-size: 60px;
            font-weight: 700;
        }

        .hero p {
            font-size: 20px;
            margin-top: 20px;
        }

        .hero .btn {
            margin-top: 25px;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 40px;
        }

        .service-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-card img {
            height: 220px;
            object-fit: cover;
        }

        .service-card .card-body {
            padding: 25px;
        }

        .service-price {
            font-size: 24px;
            font-weight: 700;
            color: #0d6efd;
        }

        .feature-box {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
        }

        .footer {
            background: #111827;
            color: white;
            padding: 30px 0;
            margin-top: 100px;
        }

    </style>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">

    <div class="container">

        <a class="navbar-brand fw-bold">
            Laundry Booking
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse"
             id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item me-2">
                    <a href="/login"
                       class="btn btn-light rounded-pill px-4">
                        Login
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/register"
                       class="btn btn-primary rounded-pill px-4">
                        Register
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>

<section class="hero">

    <div class="container">

        <h1>
            Premium Laundry Service
        </h1>

        <p>
            Fast, clean, affordable, and professional laundry booking system.
        </p>

        <a href="/booking"
           class="btn btn-primary btn-lg">
            Book Laundry Now
        </a>

    </div>

</section>

<section class="container mt-5">

    <div class="text-center mb-5">

        <h2 class="section-title">
            Our Laundry Services
        </h2>

    </div>

    <div class="row">

        <?php foreach($services as $service): ?>

        <div class="col-md-4 mb-4">

            <div class="card service-card h-100">

                <img src="https://images.unsplash.com/photo-1582735689369-4fe89db7114c?q=80&w=1200"
                     class="card-img-top">

                <div class="card-body d-flex flex-column">

                    <h4 class="fw-bold">
                        <?= $service['name'] ?>
                    </h4>

                    <p class="text-muted">
                        <?= $service['description'] ?>
                    </p>

                    <div class="mt-auto">

                        <div class="service-price mb-3">
                            Rp <?= number_format($service['price']) ?>
                        </div>

                        <a href="/booking"
                           class="btn btn-primary w-100 rounded-pill">
                            Book Service
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

</section>

<section class="container mt-5">

    <div class="text-center mb-5">

        <h2 class="section-title">
            Why Choose Us?
        </h2>

    </div>

    <div class="row g-4">

        <div class="col-md-4">

            <div class="feature-box text-center h-100">

                <h4 class="fw-bold mb-3">
                    Fast Process
                </h4>

                <p>
                    Your laundry is processed quickly with professional quality.
                </p>

            </div>

        </div>

        <div class="col-md-4">

            <div class="feature-box text-center h-100">

                <h4 class="fw-bold mb-3">
                    Affordable Price
                </h4>

                <p>
                    Premium laundry service at affordable and student-friendly prices.
                </p>

            </div>

        </div>

        <div class="col-md-4">

            <div class="feature-box text-center h-100">

                <h4 class="fw-bold mb-3">
                    Online Booking
                </h4>

                <p>
                    Easily book your laundry service anytime and anywhere.
                </p>

            </div>

        </div>

    </div>

</section>

<footer class="footer text-center">

    <div class="container">

        <h5>
            Laundry Booking System
        </h5>

        <p>
            © 2026 All Rights Reserved
        </p>

    </div>

</footer>

<script src=
"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>