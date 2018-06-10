<?php

require_once __DIR__ . '/vendor/System/Application.php';
require_once __DIR__ . '/vendor/System/FileSystem.php';

use System\Application;
use System\FileSystem;

$fileSystem = new FileSystem(__DIR__);

$app = Application::getInstance($fileSystem);

$app->run();
