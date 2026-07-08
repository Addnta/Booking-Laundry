<?php

if (! function_exists('ui_money')) {
    function ui_money($value, string $prefix = 'Rp'): string
    {
        return $prefix . ' ' . number_format((float) $value, 0, ',', '.');
    }
}

if (! function_exists('ui_labelify')) {
    function ui_labelify(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '-';
        }

        return ucwords(str_replace(['_', '-'], ' ', $value));
    }
}

if (! function_exists('ui_nav_active')) {
    function ui_nav_active(string $currentPath, string $targetPath): bool
    {
        return rtrim($currentPath, '/') === rtrim($targetPath, '/');
    }
}

if (! function_exists('ui_status_badge_class')) {
    function ui_status_badge_class(string $category, ?string $value): string
    {
        $category = strtolower($category);
        $value = strtolower((string) $value);

        $maps = [
            'booking' => [
                'pending' => 'bg-warning text-dark',
                'confirmed' => 'bg-info text-dark',
                'process' => 'bg-primary',
                'completed' => 'bg-success',
                'rejected' => 'bg-danger',
                'cancelled' => 'bg-secondary',
            ],
            'payment' => [
                'unpaid' => 'bg-warning text-dark',
                'paid' => 'bg-success',
                'failed' => 'bg-danger',
            ],
            'notification' => [
                'read' => 'bg-success',
                'unread' => 'bg-warning text-dark',
            ],
        ];

        return $maps[$category][$value] ?? 'bg-secondary';
    }
}
