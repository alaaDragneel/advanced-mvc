<?php

use System\Application;

// White List Route

$app = Application::getInstance();

$app->route->get('/', 'HomeController@index');

// /posts/alaa-dragneel/21
$app->route->get('/posts/{text}/{id}', 'Posts/PostController@index');