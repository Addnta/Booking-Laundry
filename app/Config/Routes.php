<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Auth::login');

$routes->post('/login/process', 'Auth::loginProcess');

$routes->get('/register', 'Auth::register');

$routes->post('/register/process', 'Auth::registerProcess');

$routes->get('/logout', 'Auth::logout');

$routes->get(
    '/admin/dashboard',
    'DashboardController::admin',
    ['filter' => 'adminFilter']
);

$routes->get(
    '/staff/dashboard',
    'DashboardController::staff',
    ['filter' => 'staffFilter']
);

$routes->get(
    '/customer/dashboard',
    'DashboardController::customer',
    ['filter' => 'customerFilter']
);

$routes->get(
    '/admin/services',
    'ServiceController::index',
    ['filter' => 'adminFilter']
);

$routes->get(
    '/admin/services/create',
    'ServiceController::create',
    ['filter' => 'adminFilter']
);

$routes->post(
    '/admin/services/store',
    'ServiceController::store',
    ['filter' => 'adminFilter']
);

$routes->get(
    '/admin/services/edit/(:num)',
    'ServiceController::edit/$1',
    ['filter' => 'adminFilter']
);

$routes->post(
    '/admin/services/update/(:num)',
    'ServiceController::update/$1',
    ['filter' => 'adminFilter']
);

$routes->get(
    '/admin/services/delete/(:num)',
    'ServiceController::delete/$1',
    ['filter' => 'adminFilter']
);

// Schedules (admin)
$routes->get(
    '/admin/schedules',
    'ScheduleController::index',
    ['filter' => 'adminFilter']
);
$routes->get(
    '/admin/schedules/create',
    'ScheduleController::create',
    ['filter' => 'adminFilter']
);
$routes->post(
    '/admin/schedules/store',
    'ScheduleController::store',
    ['filter' => 'adminFilter']
);
$routes->get(
    '/admin/schedules/edit/(:num)',
    'ScheduleController::edit/$1',
    ['filter' => 'adminFilter']
);
$routes->post(
    '/admin/schedules/update/(:num)',
    'ScheduleController::update/$1',
    ['filter' => 'adminFilter']
);
$routes->get(
    '/admin/schedules/delete/(:num)',
    'ScheduleController::delete/$1',
    ['filter' => 'adminFilter']
);

$routes->group('', ['filter' => 'customerFilter'], function($routes){

    $routes->get(
        '/services',
        'ServiceController::customerIndex'
    );

    $routes->get(
        '/booking',
        'BookingController::create'
    );

    $routes->post(
        '/booking/store',
        'BookingController::store'
    );

    $routes->get(
        '/my-bookings',
        'BookingController::myBookings'
    );
    $routes->post(
        '/my-bookings/cancel/(:num)',
        'BookingController::cancel/$1'
    );
    $routes->post(
        '/my-bookings/review/(:num)',
        'BookingController::submitReview/$1'
    );

    $routes->get(
        '/payment/checkout/(:num)',
        'PaymentController::checkout/$1'
    );

    $routes->get(
        '/calendar/sync/(:num)',
        'CalendarController::sync/$1'
    );

    $routes->get(
        '/calendar/download/(:num)',
        'CalendarController::downloadIcs/$1'
    );

});

$routes->group('', ['filter' => 'adminFilter'], function($routes) {
    $routes->get('/admin/notifications', 'NotificationController::index');
    $routes->get('/admin/notifications/read/(:num)', 'NotificationController::markRead/$1');
    $routes->get('/admin/notifications/mark-all', 'NotificationController::markAllRead');
    $routes->get('/admin/users', 'UserController::index');
    $routes->get('/admin/users/create', 'UserController::create');
    $routes->post('/admin/users/store', 'UserController::store');
    $routes->post('/admin/users/toggle-status/(:num)', 'UserController::toggleStatus/$1');
    $routes->post('/admin/reminders/h1', 'NotificationController::sendH1Reminder');
});

$routes->get('/api/rajaongkir/provinces', 'RajaOngkirController::provinces');
$routes->get('/api/rajaongkir/cities/(:num)', 'RajaOngkirController::cities/$1');
$routes->post('/api/rajaongkir/cost', 'RajaOngkirController::cost');

$routes->post(
    '/payment/webhook',
    'PaymentController::webhook'
);
$routes->post(
    '/api/payment/callback',
    'PaymentController::callback'
);

$routes->get(
    '/admin/bookings',
    'BookingController::adminBookings',
    ['filter' => 'adminFilter']
);

$routes->get(
    '/admin/bookings/confirm/(:num)',
    'BookingController::confirm/$1',
    ['filter' => 'adminFilter']
);

$routes->get(
    '/admin/bookings/reject/(:num)',
    'BookingController::reject/$1',
    ['filter' => 'adminFilter']
);

$routes->get(
    '/admin/bookings/edit/(:num)',
    'BookingController::edit/$1',
    ['filter' => 'adminFilter']
);

$routes->post(
    '/admin/bookings/update/(:num)',
    'BookingController::update/$1',
    ['filter' => 'adminFilter']
);

$routes->get(
    '/admin/bookings/delete/(:num)',
    'BookingController::delete/$1',
    ['filter' => 'adminFilter']
);

$routes->post(
    '/staff/bookings/update-status/(:num)',
    'BookingController::staffUpdateStatus/$1',
    ['filter' => 'staffFilter']
);

$routes->get(
    '/staff/bookings',
    'BookingController::staffBookings',
    ['filter' => 'staffFilter']
);

$routes->get(
    '/api/services',
    'Api\ServicesApiController::index'
);

$routes->get(
    '/api/booking-status/(:num)',
    'Api\BookingApiController::status/$1'
);
