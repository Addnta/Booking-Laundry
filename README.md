# Booking Service

Sistem pemesanan layanan berbasis web untuk laundry dan layanan sejenis dengan alur admin, staff, customer, pembayaran, notifikasi, dan integrasi jadwal.

## Ringkasan Teknologi

- CodeIgniter 4 dengan pola MVC
- MySQL, migration, dan seeder
- Midtrans sandbox untuk pembayaran
- PHPMailer + Gmail SMTP untuk email notifikasi
- RajaOngkir API untuk estimasi ongkir
- Google Calendar API untuk sinkron jadwal
- Endpoint API berproteksi API key di `/api/*`

## Struktur Arsitektur

- `app/Controllers` menangani alur request per role dan API
- `app/Models` berisi akses data yang konsisten
- `app/Libraries` berisi layanan custom seperti dashboard layout, WhatsApp, Google Calendar, dan notifikasi
- `app/Helpers/ui_helper.php` menyimpan helper UI seperti format uang, label status, dan active state
- `app/Views/layouts/dashboard.php` menjadi layout bersama agar UI/UX konsisten antar halaman dashboard

## Fitur Utama

- Admin: kelola service, jadwal, booking, user, notifikasi, dan ringkasan operasional
- Staff: lihat daftar booking, update status pembayaran dan progres kerja
- Customer: daftar, login, lihat layanan, booking, cek status, sync kalender, dan beri ulasan
- API: service CRUD, status booking, dan integrasi pihak ketiga

## Cara Install

1. Clone repository ke folder kerja lokal.
2. Jalankan `composer install`.
3. Salin `.env.example` atau file `env` menjadi `.env`.
4. Atur koneksi database, `app.baseURL`, `app.encryptionKey`, API key, Midtrans, RajaOngkir, Google Calendar, dan email SMTP.
5. Buat database MySQL kosong.
6. Jalankan migrasi dan seeder:
   ```bash
   php spark migrate
   php spark db:seed DatabaseSeeder
   ```
7. Pastikan folder `writable/` dan `uploads/` bisa ditulis.
8. Jalankan aplikasi:
   ```bash
   php spark serve
   ```

## Environment Penting

- `database.default.*` untuk koneksi MySQL
- `app.baseURL` untuk URL aplikasi
- `api.key` untuk endpoint API terproteksi
- `midtrans.serverKey` dan `midtrans.clientKey`
- `email.*` untuk pengiriman notifikasi
- `rajaongkir.*` untuk ongkir
- `googleCalendar.*` untuk sinkron kalender

## Catatan Operasional

- Reminder H-1 tersedia lewat CLI: `php spark reminder:h1`
- Jalankan perintah tersebut tiap hari via cron atau Task Scheduler
- Route API docs tersedia di `/api/docs`

## Skema Data

Lihat ERD lengkap di [docs/ERD.md](docs/ERD.md) atau versi editable di [docs/ERD.drawio](docs/ERD.drawio).
