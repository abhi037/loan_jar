<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check for maintenance mode
if (file_exists($maintenance = __DIR__.'/../loanjar.co.in/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Load Composer autoloader
require __DIR__.'/../loanjar.co.in/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../loanjar.co.in/bootstrap/app.php';

// Handle the request
$kernel = $app->make(Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
)->send();
$kernel->terminate($request, $response);