# ERD Booking Service

Berikut ERD logis untuk domain utama aplikasi.

```mermaid
erDiagram
    USERS ||--o{ BOOKINGS : places
    SERVICES ||--o{ BOOKINGS : selected_in
    SCHEDULES ||--o{ BOOKINGS : scheduled_for
    BOOKINGS ||--|| PAYMENTS : has
    BOOKINGS ||--o{ REVIEWS : receives
    USERS ||--o{ NOTIFICATIONS : receives

    USERS {
        bigint id PK
        string name
        string email
        string password
        string role
        string status
        datetime created_at
        datetime updated_at
    }

    SERVICES {
        bigint id PK
        string name
        text description
        decimal price
        decimal duration
        string photo
        datetime created_at
        datetime updated_at
    }

    SCHEDULES {
        bigint id PK
        bigint service_id FK
        date date
        string time_slot
        int capacity
        datetime created_at
        datetime updated_at
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
        decimal shipping_cost
        decimal total_price
        string delivery_type
        string destination_province_name
        string destination_city_name
        text destination_address
        bigint assigned_staff_id FK
        string work_proof_photo
        string google_event_id
        text notes
        datetime created_at
        datetime updated_at
    }

    PAYMENTS {
        bigint id PK
        bigint booking_id FK
        string transaction_id
        string payment_type
        string transaction_status
        decimal gross_amount
        datetime paid_at
        datetime created_at
        datetime updated_at
    }

    REVIEWS {
        bigint id PK
        bigint booking_id FK
        int rating
        text review
        datetime created_at
        datetime updated_at
    }

    NOTIFICATIONS {
        bigint id PK
        bigint user_id FK
        string type
        string title
        text message
        bool is_read
        datetime created_at
        datetime updated_at
    }
```

## Relasi Inti

- `users` ke `bookings` adalah one-to-many
- `services` ke `bookings` adalah one-to-many
- `schedules` ke `bookings` adalah one-to-many
- `bookings` ke `payments` umumnya one-to-one
- `bookings` ke `reviews` adalah one-to-many secara teknis, tetapi bisnisnya satu review per booking
- `users` ke `notifications` adalah one-to-many

## Catatan Desain

- `booking_status` dan `payment_status` dipakai sebagai state utama operasional
- `assigned_staff_id` menyimpan staff yang menangani booking
- `google_event_id` digunakan saat booking disinkronkan ke Google Calendar
- Field destinasi disiapkan untuk alur delivery/pickup dan integrasi ongkir
