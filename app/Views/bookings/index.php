<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Laundry</title>

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

        .booking-card {
            width: 100%;
            max-width: 860px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin: 0 auto;
        }

        .title {
            font-size: 38px;
            font-weight: bold;
            color: #0d6efd;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .form-control,
        .form-select {
            height: 50px;
            border-radius: 12px;
        }

        textarea.form-control {
            height: 120px;
        }

        .btn-book {
            width: 100%;
            height: 50px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            main { margin-left: 0; }
            .sidebar { position: relative; width: 100%; min-height: auto; }
            .booking-card { padding: 24px; }
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
        <li><a href="<?= base_url('/booking') ?>" class="active"><i class="fas fa-shopping-bag"></i> Booking</a></li>
        <li><a href="<?= base_url('/my-bookings') ?>"><i class="fas fa-list"></i> My Bookings</a></li>
        <li><a href="<?= base_url('/services') ?>"><i class="fas fa-concierge-bell"></i> Services</a></li>
        <li class="nav-section">Akun</li>
        <li><a href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<main>
<div class="booking-card">

    <div class="text-center mb-4">
        <h1 class="title">Booking Laundry</h1>
        <p class="subtitle">Pilih servis laundry dan masukkan berat cucian Anda</p>
    </div>

    <?php if(session()->getFlashdata('success')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/booking/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Pilih Servis</label>

            <select name="service_id" id="serviceSelect" class="form-select" required>
                <option value="" data-price="0">-- Pilih Servis --</option>

                <?php foreach($services as $service): ?>
                    <option
                        value="<?= $service['id'] ?>"
                        data-price="<?= $service['price'] ?>"
                    >
                        <?= $service['name'] ?> -
                        Rp <?= number_format($service['price'], 0, ',', '.') ?>/kg
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Pilih Jadwal</label>

            <select name="schedule_id" id="scheduleSelect" class="form-select" required>
                <option value="">-- Pilih Jadwal --</option>
                <?php foreach($schedules as $schedule): ?>
                    <?php $remaining = $schedule['remaining_capacity'] ?? 0; ?>
                    <option
                        value="<?= $schedule['id'] ?>"
                        data-service-id="<?= $schedule['service_id'] ?>"
                        data-remaining="<?= $remaining ?>"
                        <?= $remaining <= 0 ? 'disabled' : '' ?>
                    >
                        <?= date('d M Y', strtotime($schedule['date'])) ?> - <?= esc($schedule['time_slot']) ?>
                        (<?= $remaining > 0 ? $remaining . ' slot tersisa' : 'Penuh' ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <div id="scheduleCapacityInfo" class="form-text text-muted">Pilih jadwal untuk melihat sisa kapasitas.</div>
        </div>

        <div class="mb-4">
            <label class="form-label">Metode Pengiriman</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery_type" id="pickupRadio" value="pickup" checked>
                    <label class="form-check-label" for="pickupRadio">Ambil sendiri</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery_type" id="deliveryRadio" value="delivery">
                    <label class="form-check-label" for="deliveryRadio">Kirim ke alamat</label>
                </div>
            </div>
        </div>

        <div id="deliveryFields" style="display:none;">
            <div class="mb-3">
                <label class="form-label">Provinsi Tujuan</label>
                <select id="provinceSelect" name="destination_province_id" class="form-select">
                    <option value="">-- Pilih Provinsi --</option>
                    <?php foreach($provinces as $province): ?>
                    <option
                            value="<?= esc($province['id']) ?>"
                            <?= old('destination_province_id') == $province['id'] ? 'selected' : '' ?>
                        >
                            <?= esc($province['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="provinceNameInput" name="destination_province_name" value="<?= esc(old('destination_province_name')) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Kota Tujuan</label>
                <select id="citySelect" name="destination_city_id" class="form-select" required>
                    <option value="">-- Pilih Kota --</option>
                </select>
                <input type="hidden" id="cityNameInput" name="destination_city_name" value="<?= esc(old('destination_city_name')) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat Pengiriman</label>
                <textarea name="destination_address" class="form-control" placeholder="Masukkan alamat lengkap pengiriman"><?= esc(old('destination_address')) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Estimasi Ongkir</label>
                <input type="text" id="shippingCostDisplay" class="form-control" readonly value="Rp 0">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Berat Cucian (kg)</label>
            <input
                type="number"
                name="weight"
                id="weight"
                class="form-control"
                placeholder="Contoh: 5.5 atau 6.7"
                min="0.1"
                step="0.1"
                value="1.0"
                required
            >
            <div class="form-text">Masukkan berat cucian secara presisi, misalnya 5.5 kg atau 6.7 kg.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Total Harga</label>
            <input
                type="text"
                id="totalPrice"
                class="form-control"
                readonly
                value="Rp 0"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan Laundry</label>

            <textarea
                name="notes"
                class="form-control"
                placeholder="Contoh: pakaian putih dipisah, gunakan pewangi lembut..."
            ></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-book">
            Booking Sekarang
        </button>

    </form>

</div>
</main>

<script>
    const serviceSelect = document.getElementById('serviceSelect');
    const scheduleSelect = document.getElementById('scheduleSelect');
    const weightInput = document.getElementById('weight');
    const totalPriceInput = document.getElementById('totalPrice');

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    }

    const deliveryFields = document.getElementById('deliveryFields');
    const pickupRadio = document.getElementById('pickupRadio');
    const deliveryRadio = document.getElementById('deliveryRadio');
    const provinceSelect = document.getElementById('provinceSelect');
    const citySelect = document.getElementById('citySelect');
    const shippingCostDisplay = document.getElementById('shippingCostDisplay');
    const provinceNameInput = document.getElementById('provinceNameInput');
    const cityNameInput = document.getElementById('cityNameInput');
    const apiKey = '<?= esc($apiKey ?? '') ?>';
    const oldProvinceId = '<?= esc(old('destination_province_id') ?? '') ?>';
    const oldCityId = '<?= esc(old('destination_city_id') ?? '') ?>';
    const oldDeliveryType = '<?= esc(old('delivery_type') ?? 'pickup') ?>';

    function updateTotalPrice() {
        const selectedOption = serviceSelect.selectedOptions[0];
        const price = parseFloat(selectedOption?.dataset?.price || 0);
        const weight = parseFloat(weightInput.value.replace(',', '.')) || 0;
        const total = price * weight;

        totalPriceInput.value = formatCurrency(total);
        if (deliveryRadio.checked) {
            calculateShipping();
        }
    }

    function filterSchedules() {
        const selectedServiceId = serviceSelect.value;
        const scheduleOptions = Array.from(scheduleSelect.querySelectorAll('option'));

        scheduleOptions.forEach(option => {
            if (!option.value) {
                option.hidden = false;
                option.disabled = false;
                return;
            }

            const available = parseInt(option.dataset.remaining || '0', 10) > 0;
            if (selectedServiceId && option.dataset.serviceId !== selectedServiceId) {
                option.hidden = true;
            } else {
                option.hidden = false;
            }
            option.disabled = !available;
        });

        scheduleSelect.value = '';
    }

    function toggleDeliveryFields() {
        deliveryFields.style.display = deliveryRadio.checked ? 'block' : 'none';
        provinceSelect.required = deliveryRadio.checked;
        citySelect.required = deliveryRadio.checked;
        if (!deliveryRadio.checked) {
            shippingCostDisplay.value = 'Rp 0';
        } else {
            calculateShipping();
        }
    }

    function updateScheduleCapacityInfo() {
        const selectedOption = scheduleSelect.selectedOptions[0];
        const info = document.getElementById('scheduleCapacityInfo');

        if (!selectedOption || !selectedOption.value) {
            info.textContent = 'Pilih jadwal untuk melihat sisa kapasitas.';
            return;
        }

        const remaining = parseInt(selectedOption.dataset.remaining || '0', 10);
        info.textContent = remaining > 0
            ? `Jadwal ini memiliki ${remaining} slot tersisa.`
            : 'Jadwal ini sudah penuh, pilih jadwal lain.';
    }

    async function loadCities(provinceId) {
        citySelect.innerHTML = '<option value="">Memuat kota...</option>';
        if (!provinceId) {
            citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
            cityNameInput.value = '';
            return;
        }

        try {
            const response = await fetch('<?= base_url('/api/rajaongkir/cities/') ?>' + provinceId, {
                headers: {
                    ...(apiKey ? { 'X-API-KEY': apiKey } : {})
                }
            });
            const data = await response.json();
            if (data?.status !== 'success') {
                citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
                cityNameInput.value = '';
                return;
            }

            const cities = Array.isArray(data?.data) ? data.data : [];

            citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                option.dataset.cityName = city.name;
                if (oldCityId && oldCityId === city.id) {
                    option.selected = true;
                }
                citySelect.appendChild(option);
            });

            if (cities.length === 0) {
                citySelect.innerHTML = '<option value="">Kota tidak tersedia</option>';
                cityNameInput.value = '';
                return;
            }

            const selectedOption = citySelect.selectedOptions[0];
            cityNameInput.value = selectedOption?.dataset?.cityName || '';
        } catch (error) {
            citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
            cityNameInput.value = '';
        }
    }

    async function calculateShipping() {
        const destinationCityId = citySelect.value;
        const weight = parseFloat(weightInput.value.replace(',', '.')) || 0;

        if (!destinationCityId || weight <= 0) {
            shippingCostDisplay.value = 'Rp 0';
            return;
        }

        try {
            const response = await fetch('<?= base_url('/api/rajaongkir/cost') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    ...(apiKey ? { 'X-API-KEY': apiKey } : {})
                },
                body: JSON.stringify({
                    destination_city_id: destinationCityId,
                    weight: weight
                })
            });
            const result = await response.json();
            if (result?.status !== 'success') {
                shippingCostDisplay.value = 'Rp 0';
                return;
            }
            const value = result?.data?.[0]?.cost || 0;
            shippingCostDisplay.value = formatCurrency(value);
        } catch (error) {
            shippingCostDisplay.value = 'Rp 0';
        }
    }

    serviceSelect.addEventListener('change', () => {
        filterSchedules();
        updateTotalPrice();
    });

    scheduleSelect.addEventListener('change', updateScheduleCapacityInfo);
    weightInput.addEventListener('input', updateTotalPrice);
    provinceSelect.addEventListener('change', () => {
        const provinceOption = provinceSelect.selectedOptions[0];
        provinceNameInput.value = provinceOption?.textContent?.trim() || '';
        cityNameInput.value = '';
        loadCities(provinceSelect.value);
    });
    citySelect.addEventListener('change', () => {
        const provinceOption = provinceSelect.selectedOptions[0];
        provinceNameInput.value = provinceOption?.textContent?.trim() || '';
        const cityOption = citySelect.selectedOptions[0];
        cityNameInput.value = cityOption?.dataset?.cityName || cityOption?.textContent?.trim() || '';
        calculateShipping();
    });
    pickupRadio.addEventListener('change', toggleDeliveryFields);
    deliveryRadio.addEventListener('change', toggleDeliveryFields);

    filterSchedules();
    updateTotalPrice();
    toggleDeliveryFields();
    updateScheduleCapacityInfo();

    if (oldDeliveryType === 'delivery') {
        deliveryRadio.checked = true;
        pickupRadio.checked = false;
        toggleDeliveryFields();
    }

    if (oldProvinceId) {
        provinceSelect.value = oldProvinceId;
        loadCities(oldProvinceId);
        provinceNameInput.value = provinceSelect.selectedOptions[0]?.textContent?.trim() || '';
    }

    const queryParams = new URLSearchParams(window.location.search);
    const preselectedService = queryParams.get('service_id');
    if (preselectedService) {
        serviceSelect.value = preselectedService;
        filterSchedules();
        updateTotalPrice();
    }
</script>

</body>
</html>
