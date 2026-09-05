<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Support\Facades\Artisan;

Route::get('/run-emergency-migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    $migrateOutput = Artisan::output();

    Artisan::call('db:seed', [
        '--class' => 'CoaMouldingFjSeeder',
        '--force' => true,
    ]);
    $seedOutput = Artisan::output();

    return '<pre>--- MIGRATE OUTPUT ---' . PHP_EOL . $migrateOutput . PHP_EOL . PHP_EOL . '--- SEED OUTPUT ---' . PHP_EOL . $seedOutput . '</pre>';
});