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
        Schema::table('coa_items', function (Blueprint $table) {
            if (!Schema::hasColumn('coa_items', 'total_cost')) {
                $table->decimal('total_cost', 14, 2)->nullable()->after('standard_rate_per_ton');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coa_items', function (Blueprint $table) {
            //
        });
    }
};
