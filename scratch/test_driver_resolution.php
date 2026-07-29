<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    echo get_class(app('mailer')->driver('sendgrid')->getSymfonyTransport());
} catch (\Exception $e) {
    echo $e->getMessage();
} catch (\Error $e) {
    echo $e->getMessage();
}
