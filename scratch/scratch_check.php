<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use Illuminate\Support\Facades\DB;

$affected = DB::update('UPDATE setoran_pengepul SET created_at = NOW(), updated_at = NOW() WHERE created_at IS NULL');
echo "Updated {$affected} null timestamps in setoran_pengepul.\n";
