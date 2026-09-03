<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moulding_costing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moulding_costing_id')->constrained()->cascadeOnDelete();
            $table->string('batch_no');
            $table->foreignId('species_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('volume_ton', 12, 4)->default(1);
            $table->decimal('raw_cost_per_ton', 12, 2)->default(0);
            $table->decimal('subtotal_cost', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moulding_costing_items');
    }
};