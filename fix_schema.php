<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (DB::select('SHOW TABLES') as $tableObj) {
    $t = current((array)$tableObj);
    $cols = DB::select('SHOW COLUMNS FROM ' . $t);
    echo $t . ': ' . implode(', ', array_column($cols, 'Field')) . PHP_EOL;
}

