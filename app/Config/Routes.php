<?php

use CodeIgniter\Router\RouteCollection;

use App\Controllers\Pages;
use Config\Cors;
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);

// Routes API
$routes->group('api', ['filter' => 'cors:api','namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->resource('users');
    $routes->resource('profiles');
});

$routes->get('/api/docs', 'SwaggerUIController::index');