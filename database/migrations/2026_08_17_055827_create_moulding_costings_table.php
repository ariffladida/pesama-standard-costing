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
        Schema::create('moulding_costings', function (Blueprint $table) {
            $table->id();
            $table->enum('source_type', ['process', 'purchase']);
            $table->string('product_size'); // 28x133, 28x145
            $table->decimal('raw_material_cost_per_ton', 10, 2);
            $table->decimal('mfg_cost_per_ton', 10, 2);
            $table->decimal('total_cost_per_ton', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moulding_costings');
    }
};
