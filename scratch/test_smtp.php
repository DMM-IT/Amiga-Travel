<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

config([
    'mail.default' => 'smtp',
    'mail.mailers.smtp.host' => 'smtp.gmail.com',
    'mail.mailers.smtp.port' => 465,
    'mail.mailers.smtp.encryption' => 'ssl',
    'mail.mailers.smtp.username' => 'macaraigdrew99@gmail.com',
    'mail.mailers.smtp.password' => 'prirexcufzqxbsam',
]);

try {
    \Illuminate\Support\Facades\Mail::raw('Test email from system check', function ($m) {
        $m->to('macaraigdrew99@gmail.com')->subject('SMTP Test');
    });
    echo "SMTP Config is valid. Email sent successfully.\n";
} catch (\Exception $e) {
    echo "SMTP Error: " . $e->getMessage() . "\n";
}
