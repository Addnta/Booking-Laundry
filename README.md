# Booking-Laundry

Sistem pemesanan layanan berbasis web untuk booking jasa seperti laundry, salon, klinik, dan layanan sejenis.

## Tech Stack

- CodeIgniter 4
- MySQL with Migration and Seeder
- Midtrans Sandbox payment gateway
- PHPMailer + Gmail SMTP for email notification
- RajaOngkir API for shipping estimation
- Google Calendar API for schedule sync
- API key protected endpoints are available under `/api/*`
- API docs endpoint is available at `/api/docs`

## Project Notes

- Email notification uses `PHPMailer` with Gmail SMTP settings from `.env`.
- Midtrans runs in sandbox mode with `midtrans.serverKey` and `midtrans.clientKey`.
- Reminder H-1 is available as a CLI command: `php spark reminder:h1`.
- Run that command daily using your OS scheduler:
  - Linux/macOS: cron
  - Windows: Task Scheduler

## Setup

1. Copy `env` to `.env`.
2. Set database, email, API key, Midtrans, RajaOngkir, and Google Calendar configuration.
3. Run migrations and seeders.
4. Start the app from the `public` folder.

## Main Features

- Admin: manage services, schedules, bookings, users, staff, and dashboard stats
- Staff: see daily tasks, update progress, and view personal work history
- Customer: register, login, browse services, book schedules, pay via Midtrans, cancel pending bookings, and submit reviews
