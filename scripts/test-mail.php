<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Illuminate\Support\Facades\Mail::raw('Railway mail test', function ($message) {
    $message->to('macaraigdrew99@gmail.com')->subject('Railway mail test');
});

echo "Mail send attempted successfully\n";
