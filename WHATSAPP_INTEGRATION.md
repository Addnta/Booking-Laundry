# WhatsApp Integration with PetaPod

Dokumentasi lengkap untuk integrasi WhatsApp menggunakan PetaPod API dalam aplikasi Booking Service.

## Instalasi & Konfigurasi

### 1. File yang Telah Dibuat

- **Controller**: `app/Controllers/WhatsAppController.php`
- **Library**: `app/Libraries/WhatsApp.php`
- **Config**: `app/Config/WhatsApp.php`
- **Routes**: `app/Config/Routes.php` (sudah ditambahkan routes WhatsApp)

### 2. Konfigurasi Environment

Edit file `.env` dan isi konfigurasi PetaPod:

```env
#--------------------------------------------------------------------
# WHATSAPP - PETAPOD API
#--------------------------------------------------------------------
WHATSAPP_ENABLED = true
WHATSAPP_BASE_URL = 'https://servicelaundry-05a3.sg-2.podo.top/dashboard'
WHATSAPP_API_KEY = '6ab9f50e73e50ce363c5793b8f5baf67528b5384fff1cb29'
WHATSAPP_BUSINESS_PHONE = ''
```

**Keterangan**:
- `WHATSAPP_ENABLED`: Status aktivasi WhatsApp (true/false)
- `WHATSAPP_BASE_URL`: URL endpoint PetaPod API
- `WHATSAPP_API_KEY`: Authentication key dari PetaPod
- `WHATSAPP_BUSINESS_PHONE`: Nomor bisnis WhatsApp (opsional)

## Penggunaan

### 1. Mengirim Pesan Langsung

```php
$whatsapp = new \App\Libraries\WhatsApp();

$result = $whatsapp->sendMessage(
    '6281234567890',  // Nomor tujuan (format internasional)
    'Halo, ini pesan test'  // Pesan
);

if ($result['success']) {
    echo "Pesan terkirim!";
    print_r($result['data']);
} else {
    echo "Gagal: " . $result['message'];
}
```

### 2. Endpoint API Controller

#### Mengirim Pesan (Admin)
```
POST /admin/whatsapp/send
```

**Request Body (JSON)**:
```json
{
    "phone": "6281234567890",
    "message": "Halo, ini pesan dari sistem"
}
```

**Response**:
```json
{
    "status": "success",
    "message": "Message sent successfully",
    "data": {
        "messageId": "xxxxx",
        "timestamp": "2024-01-15T10:30:00Z"
    }
}
```

#### Mengirim Konfirmasi Booking
```
POST /admin/whatsapp/send-confirmation/{booking_id}
```

Otomatis mengirim pesan konfirmasi booking kepada pelanggan dengan format profesional.

#### Mengirim Reminder Booking
```
POST /admin/whatsapp/send-reminder/{booking_id}
```

Mengirim reminder tentang status pesanan kepada pelanggan.

#### Mengirim Notifikasi Selesai
```
POST /admin/whatsapp/send-completion/{booking_id}
```

Mengirim notifikasi bahwa pesanan telah selesai diproses.

#### Mengirim Pesan Massal
```
POST /admin/whatsapp/send-bulk
```

**Request Body (JSON)**:
```json
{
    "recipients": ["6281234567890", "6282345678901", "6283456789012"],
    "message": "Pesan untuk semua penerima"
}
```

#### Cek Status API
```
GET /admin/whatsapp/status
```

Mengecek koneksi dan status PetaPod API.

### 3. Menggunakan di Controller/Model

```php
use App\Libraries\WhatsApp;
use App\Models\BookingModel;
use App\Models\UserModel;

class BookingController extends BaseController
{
    protected WhatsApp $whatsapp;
    
    public function __construct()
    {
        $this->whatsapp = new WhatsApp();
    }
    
    public function confirmBooking($bookingId)
    {
        $booking = (new BookingModel())->find($bookingId);
        $customer = (new UserModel())->find($booking['customer_id']);
        
        // Kirim pesan otomatis
        $message = $this->whatsapp->buildBookingConfirmationMessage($booking, $customer);
        $result = $this->whatsapp->sendMessage($customer['phone'], $message);
        
        if ($result['success']) {
            // Update status booking
            (new BookingModel())->update($bookingId, [
                'status' => 'confirmed',
                'whatsapp_notified' => true
            ]);
        }
    }
}
```

## Format Nomor Telepon

Sistem secara otomatis memformat nomor telepon ke format internasional:

```
Input: 081234567890     → Output: 6281234567890
Input: +6281234567890   → Output: 6281234567890
Input: 6281234567890    → Output: 6281234567890
```

## Template Pesan

### 1. Konfirmasi Booking
```
Halo {nama_customer}, 👋

Terima kasih telah melakukan pemesanan! ✅
Booking Anda telah dikonfirmasi.

📋 Detail Pesanan:
Kode Booking: {booking_code}
Tanggal: {booking_date}
Layanan: {service_name}
Total: Rp {total_price}

📍 Lokasi Pengambilan:
{pickup_location}

⏰ Estimasi:
{estimated_completion}

Hubungi kami jika ada pertanyaan.
Terima kasih! 🙏
```

### 2. Reminder Booking
```
Halo {nama_customer}, 👋

Pengingat: Pesanan Anda sedang diproses! ⏳

📋 Detail Pesanan:
Kode Booking: {booking_code}
Status: {status}
Estimasi Selesai: {estimated_completion}

Terima kasih atas kepercayaan Anda! 🙏
```

### 3. Notifikasi Selesai
```
Halo {nama_customer}, 👋

Pesanan Anda telah selesai! ✅

📋 Detail Pesanan:
Kode Booking: {booking_code}
Status: Selesai
Tanggal Selesai: {date}
Total: Rp {total_price}

Pesanan siap diambil. Silakan hubungi kami untuk pengambilan.
Terima kasih! 🙏
```

## Error Handling

Sistem mencatat semua error di file log:

```
storage/logs/log-{date}.log
```

Contoh error log:
```
ERROR - 2024-01-15 10:30:00 --> WhatsApp Send Error: Connection timeout
ERROR - 2024-01-15 10:30:05 --> WhatsApp Template Error: Invalid phone format
```

## Testing

### Test Mengirim Pesan Manual

```php
// Di file test atau tinker console
$whatsapp = new \App\Libraries\WhatsApp();

// Test send message
$result = $whatsapp->sendMessage('6281234567890', 'Test message');
var_dump($result);

// Test status
$status = $whatsapp->getStatus();
var_dump($status);
```

### Test via Browser

1. Buka admin dashboard
2. Masuk ke menu WhatsApp
3. Klik "Kirim Pesan" dan masukkan nomor tujuan
4. Kirim dan lihat hasilnya

## Troubleshooting

### API Key Error
```json
{
    "status": "error",
    "message": "Unauthorized"
}
```
**Solusi**: Pastikan `WHATSAPP_API_KEY` di `.env` benar dan sesuai dengan PetaPod.

### Invalid Phone Format
```json
{
    "status": "error",
    "message": "Invalid phone format"
}
```
**Solusi**: Gunakan format nomor internasional (dimulai dengan 62).

### Connection Timeout
```json
{
    "status": "error",
    "message": "Error sending message: Connection timeout"
}
```
**Solusi**: Cek koneksi internet dan status PetaPod API di `GET /admin/whatsapp/status`.

## Keamanan

### Best Practices

1. **Jangan expose API key** di kode public
   - Selalu simpan di `.env` file
   - Jangan commit `.env` ke git

2. **Gunakan HTTPS**
   - Pastikan koneksi ke PetaPod API menggunakan HTTPS

3. **Validasi Input**
   - Selalu validasi nomor telepon dan isi pesan
   - Gunakan filter dan validasi CodeIgniter

4. **Rate Limiting**
   - Implementasikan rate limiting untuk mencegah spam
   - Tambahkan delay antar pengiriman pesan massal

5. **Log Audit**
   - Catat semua pengiriman pesan
   - Monitor penggunaan API

## Fitur Lanjutan

### Custom Message Builder

Buat custom message format:

```php
public function sendCustom($phone, $title, $data)
{
    $message = $this->buildCustomMessage($title, $data);
    return $this->whatsapp->sendMessage($phone, $message);
}

private function buildCustomMessage($title, $data)
{
    $msg = "*" . $title . "*\n\n";
    foreach ($data as $key => $value) {
        $msg .= "$key: $value\n";
    }
    return $msg;
}
```

### Schedule Messages (Opsional)

Implementasi scheduled messages menggunakan CodeIgniter Commands:

```php
// app/Commands/SendH1Reminders.php
public function run(array $params = [])
{
    $whatsapp = new WhatsApp();
    $bookings = $this->bookingModel->getUpcomingBookings();
    
    foreach ($bookings as $booking) {
        $message = $whatsapp->buildReminderMessage($booking, $customer);
        $whatsapp->sendMessage($customer['phone'], $message);
    }
}
```

## Support & Documentation

- **PetaPod Documentation**: https://petapod.io/docs
- **CodeIgniter 4**: https://codeigniter.com/docs
- **WhatsApp Business API**: https://www.whatsapp.com/business/api

## Changelog

### v1.0.0 (2024-01-15)
- Initial WhatsApp integration
- Support untuk send message, template, bulk messages
- Booking confirmation, reminder, dan completion messages
- Error handling dan logging
- Configuration management via .env
