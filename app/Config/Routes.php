<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Pengeluaran::index');

// SEARCH & LIST pakai GET
$routes->get('pengeluaran/search', 'Pengeluaran::search');
$routes->get('pengeluaran/list', 'Pengeluaran::list');

// CREATE & UPDATE pakai POST
$routes->post('pengeluaran/create', 'Pengeluaran::create');
$routes->post('pengeluaran/update/(:num)', 'Pengeluaran::update/$1');

// DELETE juga pakai POST (sesuai AJAX di View)
$routes->post('pengeluaran/delete/(:num)', 'Pengeluaran::delete/$1');
