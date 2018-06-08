<?php

require_once __DIR__ . '/vendor/System/Application.php';
require_once __DIR__ . '/vendor/System/FileSystem.php';

use System\Application;
use System\FileSystem;
use System\TestVendor;
use App\TestApp;

$fileSystem = new FileSystem(__DIR__);

$app = new Application($fileSystem);
