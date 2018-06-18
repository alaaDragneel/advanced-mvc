<?php

use System\Application;

// White List Route

$app = Application::getInstance();

// Admin Routes
$app->route->get('/', 'HomeController@index');

// Not Found Routes
$app->route->get('/404', 'NotFoundController');
$app->route->notFound('/404');