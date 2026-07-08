<?php

namespace App\Libraries;

class DashboardLayout
{
    public function make(string $role, array $overrides = []): array
    {
        $profiles = [
            'admin' => [
                'sidebarBrand' => [
                    'icon' => 'fa-soap',
                    'title' => 'Laundry Admin',
                    'subtitle' => 'Control center operasional',
                ],
                'pageKicker' => 'Administrative dashboard',
                'pageTitle' => 'Dashboard Admin',
                'pageSubtitle' => 'Pantau layanan, booking, pengguna, dan notifikasi dari satu pusat kendali yang rapi.',
                'pageActions' => [
                    [
                        'label' => 'Tambah Service',
                        'href' => base_url('/admin/services/create'),
                        'icon' => 'fa-plus',
                        'class' => 'btn-primary',
                    ],
                    [
                        'label' => 'Lihat Booking',
                        'href' => base_url('/admin/bookings'),
                        'icon' => 'fa-calendar-check',
                        'class' => 'btn-outline-dark',
                    ],
                ],
                'sidebarSections' => [
                    [
                        'title' => 'Menu Utama',
                        'items' => [
                            ['label' => 'Dashboard', 'icon' => 'fa-chart-line', 'path' => '/admin/dashboard', 'href' => base_url('/admin/dashboard')],
                            ['label' => 'Bookings', 'icon' => 'fa-calendar-check', 'path' => '/admin/bookings', 'href' => base_url('/admin/bookings')],
                        ],
                    ],
                    [
                        'title' => 'Management',
                        'items' => [
                            ['label' => 'Services', 'icon' => 'fa-concierge-bell', 'path' => '/admin/services', 'href' => base_url('/admin/services')],
                            ['label' => 'Schedules', 'icon' => 'fa-clock', 'path' => '/admin/schedules', 'href' => base_url('/admin/schedules')],
                            ['label' => 'Users', 'icon' => 'fa-users', 'path' => '/admin/users', 'href' => base_url('/admin/users')],
                        ],
                    ],
                    [
                        'title' => 'Lainnya',
                        'items' => [
                            ['label' => 'Notifications', 'icon' => 'fa-bell', 'path' => '/admin/notifications', 'href' => base_url('/admin/notifications')],
                            ['label' => 'Logout', 'icon' => 'fa-right-from-bracket', 'path' => '/logout', 'href' => base_url('/logout')],
                        ],
                    ],
                ],
                'theme' => [
                    'sidebarStart' => '#0f172a',
                    'sidebarEnd' => '#1d4ed8',
                    'accent' => '#1d4ed8',
                ],
            ],
            'staff' => [
                'sidebarBrand' => [
                    'icon' => 'fa-user-tie',
                    'title' => 'Staff Laundry',
                    'subtitle' => 'Operasional harian',
                ],
                'pageKicker' => 'Operational dashboard',
                'pageTitle' => 'Dashboard Staff',
                'pageSubtitle' => 'Kelola booking yang masuk, update progres, dan pantau pekerjaan hari ini dengan alur yang jelas.',
                'pageActions' => [
                    [
                        'label' => 'Lihat Booking',
                        'href' => base_url('/staff/bookings'),
                        'icon' => 'fa-list-check',
                        'class' => 'btn-primary',
                    ],
                    [
                        'label' => 'Refresh',
                        'href' => base_url('/staff/dashboard'),
                        'icon' => 'fa-rotate-right',
                        'class' => 'btn-outline-dark',
                    ],
                ],
                'sidebarSections' => [
                    [
                        'title' => 'Menu',
                        'items' => [
                            ['label' => 'Dashboard', 'icon' => 'fa-chart-simple', 'path' => '/staff/dashboard', 'href' => base_url('/staff/dashboard')],
                            ['label' => 'Daftar Booking', 'icon' => 'fa-calendar-check', 'path' => '/staff/bookings', 'href' => base_url('/staff/bookings')],
                        ],
                    ],
                    [
                        'title' => 'Lainnya',
                        'items' => [
                            ['label' => 'Logout', 'icon' => 'fa-right-from-bracket', 'path' => '/logout', 'href' => base_url('/logout')],
                        ],
                    ],
                ],
                'theme' => [
                    'sidebarStart' => '#14532d',
                    'sidebarEnd' => '#22c55e',
                    'accent' => '#16a34a',
                ],
            ],
            'customer' => [
                'sidebarBrand' => [
                    'icon' => 'fa-user',
                    'title' => 'Customer Panel',
                    'subtitle' => 'Booking laundry lebih cepat',
                ],
                'pageKicker' => 'Customer dashboard',
                'pageTitle' => 'Dashboard Customer',
                'pageSubtitle' => 'Pilih layanan, buat booking, dan pantau riwayat transaksi dalam tampilan yang lebih ringkas.',
                'pageActions' => [
                    [
                        'label' => 'Booking Sekarang',
                        'href' => base_url('/booking'),
                        'icon' => 'fa-bag-shopping',
                        'class' => 'btn-primary',
                    ],
                    [
                        'label' => 'Lihat Layanan',
                        'href' => base_url('/services'),
                        'icon' => 'fa-concierge-bell',
                        'class' => 'btn-outline-dark',
                    ],
                ],
                'sidebarSections' => [
                    [
                        'title' => 'Menu',
                        'items' => [
                            ['label' => 'Dashboard', 'icon' => 'fa-home', 'path' => '/customer/dashboard', 'href' => base_url('/customer/dashboard')],
                            ['label' => 'Booking', 'icon' => 'fa-bag-shopping', 'path' => '/booking', 'href' => base_url('/booking')],
                            ['label' => 'My Bookings', 'icon' => 'fa-list', 'path' => '/my-bookings', 'href' => base_url('/my-bookings')],
                            ['label' => 'Services', 'icon' => 'fa-concierge-bell', 'path' => '/services', 'href' => base_url('/services')],
                        ],
                    ],
                    [
                        'title' => 'Akun',
                        'items' => [
                            ['label' => 'Logout', 'icon' => 'fa-right-from-bracket', 'path' => '/logout', 'href' => base_url('/logout')],
                        ],
                    ],
                ],
                'theme' => [
                    'sidebarStart' => '#1d4ed8',
                    'sidebarEnd' => '#0f766e',
                    'accent' => '#0ea5e9',
                ],
            ],
        ];

        $profile = $profiles[$role] ?? $profiles['customer'];

        return array_replace_recursive($profile, $overrides);
    }
}
