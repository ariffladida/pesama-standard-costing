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
        Schema::create('st_costings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('species_id')->constrained('species')->cascadeOnDelete();
            $table->string('batch_no')->nullable();
            $table->decimal('log_cost_per_ton', 10, 2);
            $table->decimal('transport_cost_per_ton', 10, 2)->default(68.00);
            $table->decimal('mfg_salary_cost', 10, 2)->default(0.00);
            $table->decimal('mfg_expenses_cost', 10, 2)->default(0.00);
            $table->decimal('total_avg_cost_per_ton', 10, 2);
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('st_costings');
    }
};
