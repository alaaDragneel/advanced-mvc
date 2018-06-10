<?php

use System\Application;

// White List Route

$app = Application::getInstance();

$app->route->get('/', 'Main\Home@index');

// /posts/alaa-dragneel/21
$app->route->get('/posts/{text}/{id}', 'Posts/PostController@index');