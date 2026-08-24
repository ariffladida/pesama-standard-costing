<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('st_costings', function (Blueprint $table) {
            if (!Schema::hasColumn('st_costings', 'fixed_cost_per_ton')) {
                $table->decimal('fixed_cost_per_ton', 10, 2)->default(0.00)->after('log_cost_per_ton');
            }
            if (!Schema::hasColumn('st_costings', 'variable_cost_per_ton')) {
                $table->decimal('variable_cost_per_ton', 10, 2)->default(0.00)->after('fixed_cost_per_ton');
            }
            if (!Schema::hasColumn('st_costings', 'manufacturing_cost_per_ton')) {
                $table->decimal('manufacturing_cost_per_ton', 10, 2)->default(0.00)->after('variable_cost_per_ton');
            }
            if (!Schema::hasColumn('st_costings', 'has_kd')) {
                $table->boolean('has_kd')->default(false)->after('total_avg_cost_per_ton');
            }
            if (!Schema::hasColumn('st_costings', 'kd_cost_per_ton')) {
                $table->decimal('kd_cost_per_ton', 10, 2)->default(0.00)->after('has_kd');
            }
            if (!Schema::hasColumn('st_costings', 'has_cutting')) {
                $table->boolean('has_cutting')->default(false)->after('kd_cost_per_ton');
            }
            if (!Schema::hasColumn('st_costings', 'cutting_cost_per_ton')) {
                $table->decimal('cutting_cost_per_ton', 10, 2)->default(0.00)->after('has_cutting');
            }
            if (!Schema::hasColumn('st_costings', 'adjusted_cost_per_ton')) {
                $table->decimal('adjusted_cost_per_ton', 10, 2)->default(0.00)->after('cutting_cost_per_ton');
            }
            if (!Schema::hasColumn('st_costings', 'market_type')) {
                $table->string('market_type')->default('Local')->after('adjusted_cost_per_ton');
            }
            if (!Schema::hasColumn('st_costings', 'target_margin_percentage')) {
                $table->decimal('target_margin_percentage', 5, 2)->default(15.00)->after('market_type');
            }
            if (!Schema::hasColumn('st_costings', 'benchmark_price_per_ton')) {
                $table->decimal('benchmark_price_per_ton', 10, 2)->default(0.00)->after('target_margin_percentage');
            }
            if (!Schema::hasColumn('st_costings', 'actual_selling_price_per_ton')) {
                $table->decimal('actual_selling_price_per_ton', 10, 2)->nullable()->after('benchmark_price_per_ton');
            }
            if (!Schema::hasColumn('st_costings', 'down_value_reason')) {
                $table->text('down_value_reason')->nullable()->after('actual_selling_price_per_ton');
            }
            if (!Schema::hasColumn('st_costings', 'approval_status')) {
                $table->string('approval_status')->default('Approved')->after('down_value_reason');
            }
        });
    }

    public function down(): void
    {
        // Tiada tindakan pembatalan diperlukan
    }
};