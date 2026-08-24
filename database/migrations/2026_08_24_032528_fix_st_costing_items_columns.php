<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('st_costing_items', function (Blueprint $table) {
            if (!Schema::hasColumn('st_costing_items', 'st_costing_id')) {
                $table->unsignedBigInteger('st_costing_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('st_costing_items', 'batch_no')) {
                $table->string('batch_no')->nullable()->after('st_costing_id');
            }
            if (!Schema::hasColumn('st_costing_items', 'species_id')) {
                $table->unsignedBigInteger('species_id')->nullable()->after('batch_no');
            }
            if (!Schema::hasColumn('st_costing_items', 'category')) {
                $table->string('category')->default('Local')->after('species_id');
            }
            if (!Schema::hasColumn('st_costing_items', 'volume_ton')) {
                $table->decimal('volume_ton', 10, 2)->default(1.00)->after('category');
            }
            if (!Schema::hasColumn('st_costing_items', 'log_cost_per_ton')) {
                $table->decimal('log_cost_per_ton', 10, 2)->default(0.00)->after('volume_ton');
            }
            if (!Schema::hasColumn('st_costing_items', 'subtotal_cost')) {
                $table->decimal('subtotal_cost', 10, 2)->default(0.00)->after('log_cost_per_ton');
            }
        });
    }

    public function down(): void
    {
        // Tiada tindakan pembatalan diperlukan
    }
};