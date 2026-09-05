<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-emergency-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = Artisan::output();
    } catch (\Throwable $e) {
        $migrateOutput = 'Error: ' . $e->getMessage();
    }

    $fjCostingsExists = Schema::hasTable('fj_costings');
    $fjCostingItemsExists = Schema::hasTable('fj_costing_items');

    return '<pre>' .
        '--- MIGRATE OUTPUT ---' . PHP_EOL . $migrateOutput . PHP_EOL . PHP_EOL .
        '--- STATUS JADUAL ---' . PHP_EOL .
        'fj_costings: ' . ($fjCostingsExists ? 'Wujud (OK)' : 'Tiada') . PHP_EOL .
        'fj_costing_items: ' . ($fjCostingItemsExists ? 'Wujud (OK)' : 'Tiada') .
        '</pre>';
});