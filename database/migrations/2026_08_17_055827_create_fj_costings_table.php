<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('fj_costings')) {
            Schema::create('fj_costings', function (Blueprint $table) {
                $table->id();
                $table->string('category')->default('Off-Cut Recovery / Short Length');
                $table->string('profile_size');
                $table->decimal('kd_cost_per_ton', 12, 2)->default(120.00);
                $table->string('market_type')->default('Local');
                $table->decimal('target_margin_percentage', 5, 2)->default(20.00);
                $table->decimal('actual_selling_price_per_ton', 12, 2)->nullable();
                $table->string('approval_status')->default('Approved');
                $table->text('down_value_reason')->nullable();
                $table->decimal('raw_material_cost_per_ton', 12, 2)->default(0.00);
                $table->decimal('manufacturing_cost_per_ton', 12, 2)->default(687.86);
                $table->decimal('total_cost_per_ton', 12, 2)->default(0.00);
                $table->decimal('benchmark_price_per_ton', 12, 2)->default(0.00);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fj_costing_items')) {
            Schema::create('fj_costing_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('fj_costing_id')->constrained('fj_costings')->cascadeOnDelete();
                $table->string('batch_no');
                $table->foreignId('species_id')->constrained('species')->cascadeOnDelete();
                $table->decimal('volume_ton', 12, 2)->default(1.00);
                $table->decimal('raw_cost_per_ton', 12, 2)->default(0.00);
                $table->decimal('subtotal_cost', 14, 2)->default(0.00);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fj_costings');
    }
};
