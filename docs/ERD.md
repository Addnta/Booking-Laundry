# ERD Booking Service

Berikut gambar ERD referensi yang Anda kirim.

![ERD Laundry](ERD.png)

## Entitas

- `User`: menyimpan data akun pengguna seperti `name`, `email`, `password`, `role`, `status`, dan `phone`.
- `Schedule`: menyimpan jadwal layanan dengan atribut `service_id`, `date`, `time_slot`, dan `capacity`.
- `Services`: menyimpan data layanan seperti `nama`, `duration`, `description`, `price`, dan `photo`.
- `Bookings`: menyimpan transaksi booking dengan atribut `booking_code`, `schedule_id`, `service_id`, `user_id`, `booking_status`, `payment_status`, `notes`, dan `total_price`.
- `Reviews`: menyimpan ulasan pelanggan berdasarkan booking yang sudah selesai.
- `Notification`: menyimpan notifikasi sistem untuk user.
- `Payments`: menyimpan data pembayaran yang terkait ke booking.

## Relasi

- `User` memberikan `Bookings`.
- `Services` memiliki `Schedule`.
- `Schedule` digunakan dalam `Bookings`.
- `Bookings` termasuk dalam `Payments`.
- `Bookings` diberi `Reviews`.
- `User` menerima `Notification`.

## Catatan Implementasi

- Gambar ERD ini disimpan di [docs/ERD.png](ERD.png).
- Struktur atribut pada dokumen ini mengikuti desain referensi visual yang Anda kirim.
- Untuk struktur database aktif di aplikasi, sebagian field tambahan masih mengikuti migration yang sudah ada di kode CI4.
