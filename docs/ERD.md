# ERD Booking Service

Berikut ERD yang mengikuti bentuk referensi gambar, tetapi disesuaikan dengan struktur migration yang benar-benar dipakai di aplikasi.

```mermaid
erDiagram
    USERS ||--o{ BOOKINGS : memberikan
    SERVICES ||--o{ SCHEDULES : memiliki
    SCHEDULES ||--o{ BOOKINGS : digunakan_dalam
    BOOKINGS ||--|| PAYMENTS : termasuk_dalam
    BOOKINGS ||--o{ REVIEWS : diberi_review
    USERS ||--o{ NOTIFICATIONS : menerima

    USERS {
        bigint id PK
        string name
        string email
        string password
        string role
        string phone
        string status
        datetime created_at
        datetime updated_at
    }

    SERVICES {
        bigint id PK
        string name
        text description
        decimal price
        int duration
        string photo
        datetime created_at
        datetime updated_at
    }

    SCHEDULES {
        bigint id PK
        bigint service_id FK
        date date
        time time_slot
        int capacity
        datetime created_at
    }

    BOOKINGS {
        bigint id PK
        string booking_code
        bigint user_id FK
        bigint service_id FK
        bigint schedule_id FK
        string booking_status
        string payment_status
        decimal weight
        text notes
        decimal total_price
        datetime created_at
        datetime updated_at
    }

    PAYMENTS {
        bigint id PK
        bigint booking_id FK
        string order_id
        text snap_token
        string payment_type
        string transaction_status
        decimal gross_amount
        datetime paid_at
        datetime created_at
    }

    REVIEWS {
        bigint id PK
        bigint booking_id FK
        int rating
        text review
        datetime created_at
    }

    NOTIFICATIONS {
        bigint id PK
        bigint user_id FK
        string type
        string title
        text message
        bool is_read
        datetime created_at
    }
```

## Makna Relasi

- `Users` memberikan `Bookings`
- `Services` memiliki `Schedules`
- `Schedules` digunakan dalam `Bookings`
- `Bookings` termasuk dalam `Payments`
- `Bookings` diberi `Reviews`
- `Users` menerima `Notifications`

## Catatan Desain

- Diagram ini sengaja dibuat mendekati tampilan ERD referensi yang Anda kirim.
- Nama atribut mengikuti migration supaya dokumentasi, model, dan database tetap konsisten.
- Jika Anda mau, saya bisa lanjut ubah ini menjadi file `.drawio` agar tampilannya benar-benar sama seperti gambar referensi.
