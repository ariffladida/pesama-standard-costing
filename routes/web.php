<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-emergency-migrate', function () {
    // 1. Nyahaktif foreign key check supaya boleh drop tanpa halangan
    DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

    // 2. Padam jadual FJ lama sepenuhnya
    Schema::dropIfExists('fj_costing_items');
    Schema::dropIfExists('fj_costings');

    // 3. Buang sebarang rekod berkaitan fj dari table migrations
    DB::table('migrations')->where('migration', 'like', '%fj_costing%')->delete();

    // 4. Bina jadual induk: fj_costings
    DB::statement("
        CREATE TABLE `fj_costings` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `category` varchar(255) NOT NULL DEFAULT 'Off-Cut Recovery / Short Length',
            `profile_size` varchar(255) NOT NULL,
            `kd_cost_per_ton` decimal(12,2) NOT NULL DEFAULT '120.00',
            `market_type` varchar(255) NOT NULL DEFAULT 'Local',
            `target_margin_percentage` decimal(5,2) NOT NULL DEFAULT '20.00',
            `actual_selling_price_per_ton` decimal(12,2) DEFAULT NULL,
            `approval_status` varchar(255) NOT NULL DEFAULT 'Approved',
            `down_value_reason` text DEFAULT NULL,
            `raw_material_cost_per_ton` decimal(12,2) NOT NULL DEFAULT '0.00',
            `manufacturing_cost_per_ton` decimal(12,2) NOT NULL DEFAULT '687.86',
            `total_cost_per_ton` decimal(12,2) NOT NULL DEFAULT '0.00',
            `benchmark_price_per_ton` decimal(12,2) NOT NULL DEFAULT '0.00',
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 5. Bina jadual anak: fj_costing_items
    DB::statement("
        CREATE TABLE `fj_costing_items` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `fj_costing_id` bigint unsigned NOT NULL,
            `batch_no` varchar(255) NOT NULL,
            `species_id` bigint unsigned NOT NULL,
            `volume_ton` decimal(12,2) NOT NULL DEFAULT '1.00',
            `raw_cost_per_ton` decimal(12,2) NOT NULL DEFAULT '0.00',
            `subtotal_cost` decimal(14,2) NOT NULL DEFAULT '0.00',
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            KEY `fj_costing_items_fj_costing_id_foreign` (`fj_costing_id`),
            KEY `fj_costing_items_species_id_foreign` (`species_id`),
            CONSTRAINT `fj_costing_items_fj_costing_id_fk` FOREIGN KEY (`fj_costing_id`) REFERENCES `fj_costings` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 6. Aktifkan semula foreign key check
    DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

    // 7. Masukkan rekod batch migration supaya Laravel tahu jadual sudah siap
    $batch = (DB::table('migrations')->max('batch') ?? 0) + 1;
    DB::table('migrations')->insert([
        'migration' => '2026_03_05_000001_create_fj_costings_table',
        'batch' => $batch,
    ]);

    return response()->json([
        'status' => 'Reset dan binaan semula FJ BERJAYA!',
        'fj_costings' => Schema::hasTable('fj_costings'),
        'fj_costing_items' => Schema::hasTable('fj_costing_items'),
    ]);
});