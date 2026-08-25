<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coa_items', function (Blueprint $table) {
            $table->id();
            $table->string('coa_code')->unique();
            $table->string('name');
            $table->string('cost_type')->default('Fixed');      // Fixed, Variable, Summary, Balance
            $table->string('basis_type')->default('Contract');  // Contract, Historical, Summary
            $table->decimal('standard_rate_per_ton', 10, 2)->default(0.00);
            $table->boolean('is_reducible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coa_items');
    }
};