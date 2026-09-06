<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pastikan jadual st_costings wujud
        if (!Schema::hasTable('st_costings')) {
            Schema::create('st_costings', function (Blueprint $table) {
                $table->id();
                $table->boolean('has_kd')->default(false);
                $table->decimal('kd_cost_per_ton', 12, 2)->default(0.00);
                $table->boolean('has_cutting')->default(false);
                $table->decimal('cutting_cost_per_ton', 12, 2)->default(0.00);
                $table->string('market_type', 50)->default('Local');
                $table->decimal('target_margin_percentage', 5, 2)->default(15.00);
                $table->decimal('actual_selling_price_per_ton', 12, 2)->nullable();
                $table->string('approval_status', 50)->default('Approved');
                $table->text('down_value_reason')->nullable();
                $table->decimal('log_cost_per_ton', 12, 2)->default(0.00);
                $table->decimal('manufacturing_cost_per_ton', 12, 2)->default(282.80);
                $table->decimal('total_base_cost_per_ton', 12, 2)->default(0.00);
                $table->decimal('adjusted_cost_per_ton', 12, 2)->default(0.00);
                $table->decimal('benchmark_price_per_ton', 12, 2)->default(0.00);
                $table->timestamps();
            });
        }

        // 2. Pastikan jadual st_costing_items wujud
        if (!Schema::hasTable('st_costing_items')) {
            Schema::create('st_costing_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('st_costing_id')->constrained('st_costings')->cascadeOnDelete();
                $table->string('batch_no', 100);
                $table->foreignId('species_id')->nullable();
                $table->decimal('volume_ton', 12, 2)->default(1.00);
                $table->decimal('log_cost_per_ton', 12, 2)->default(0.00);
                $table->decimal('subtotal_cost', 14, 2)->default(0.00);
                $table->timestamps();
            });
        }

        // 3. Bina jadual grade_breakdowns yang hilang
        if (!Schema::hasTable('grade_breakdowns')) {
            Schema::create('grade_breakdowns', function (Blueprint $table) {
                $table->id();
                $table->foreignId('st_costing_id')->constrained('st_costings')->cascadeOnDelete();
                $table->foreignId('grade_id')->nullable();
                $table->decimal('cost_per_ton', 12, 2)->default(0.00);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_breakdowns');
        Schema::dropIfExists('st_costing_items');
        Schema::dropIfExists('st_costings');
    }
};