<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$request = Illuminate\Http\Request::create('/api/all-schedules', 'GET');
$response = app()->handle($request);
echo substr($response->getContent(), 0, 1000);
