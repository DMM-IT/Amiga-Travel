<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$app->make('db')->table('migrations')->insert([
    'migration' => '2026_07_10_000001_add_mode_and_operator_to_ferry_routes',
    'batch' => 2,
]);
echo "inserted\n";
