<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->get('/', 'Pengeluaran::index');
$routes->get('pengeluaran/search', 'Pengeluaran::search');
$routes->get('/pengeluaran/list', 'Pengeluaran::list');
$routes->post('/pengeluaran/create', 'Pengeluaran::create');
$routes->post('/pengeluaran/update/(:num)', 'Pengeluaran::update/$1');
$routes->get('/pengeluaran/delete/(:num)', 'Pengeluaran::delete/$1');

