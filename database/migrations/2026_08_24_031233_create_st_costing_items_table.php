<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('st_costing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('st_costing_id')->constrained('st_costings')->cascadeOnDelete();
            $table->string('batch_no');                       // Batch 1, Batch 2, dll.
            $table->foreignId('species_id')->constrained('species'); // Spesies A, Spesies B, dll.
            $table->string('category')->default('Local');     // Category / Log Grade
            $table->decimal('volume_ton', 8, 2)->default(1.00); // Kuantiti (Tan)
            $table->decimal('log_cost_per_ton', 10, 2)->default(0.00); // Kos Balak per Tan
            $table->decimal('subtotal_cost', 10, 2)->default(0.00);    // Kos Keseluruhan Item
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('st_costing_items');
    }
};