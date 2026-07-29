<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

\App\Models\User::updateOrCreate(
    ['email' => 'arieskingnieto@gmail.com'],
    [
        'name' => 'Aries King',
        'password' => bcrypt('password'),
        'is_admin' => false,
        'is_staff' => false,
    ]
);
echo "User seeded successfully!\n";
