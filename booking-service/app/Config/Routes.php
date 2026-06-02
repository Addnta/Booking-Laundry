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

$routes->get('/admin/services', 'ServiceController::index');
$routes->get('/admin/services/create', 'ServiceController::create');
$routes->post('/admin/services/store', 'ServiceController::store');
$routes->get('/admin/services/edit/(:num)', 'ServiceController::edit/$1');
$routes->post('/admin/services/update/(:num)', 'ServiceController::update/$1');
$routes->get('/admin/services/delete/(:num)', 'ServiceController::delete/$1');
$routes->post('/admin/services/store', 'ServiceController::store');
$routes->get('/admin/services/edit/(:num)', 'ServiceController::edit/$1');
$routes->post('/admin/services/update/(:num)', 'ServiceController::update/$1');
$routes->get('/admin/services/delete/(:num)', 'ServiceController::delete/$1');

$routes->get(
    '/admin/dashboard',
    'Dashboard::admin',
    ['filter' => 'adminFilter']
);

$routes->get(
    '/staff/dashboard',
    'Dashboard::staff',
    ['filter' => 'staffFilter']
);

$routes->get(
    '/customer/dashboard',
    'Dashboard::customer',
    ['filter' => 'customerFilter']
);