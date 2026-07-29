<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    Mail::raw('Test', function($m) {
        $m->to('macaraigdrew99@gmail.com')->subject('Test');
    });
    echo 'Success';
} catch(\Exception $e) {
    echo get_class($e) . ': ' . $e->getMessage();
} catch(\Error $e) {
    echo get_class($e) . ': ' . $e->getMessage();
}
