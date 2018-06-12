<?php

use System\Application;

// White List Route

$app = Application::getInstance();

$app->route->get('/', 'HomeController@index');