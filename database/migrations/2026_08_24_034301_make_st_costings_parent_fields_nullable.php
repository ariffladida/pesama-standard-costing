<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('st_costings', function (Blueprint $table) {
            $table->unsignedBigInteger('species_id')->nullable()->change();
            $table->string('category')->nullable()->change();
            $table->string('batch_no')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('st_costings', function (Blueprint $table) {
            $table->unsignedBigInteger('species_id')->nullable(false)->change();
            $table->string('category')->nullable(false)->change();
            $table->string('batch_no')->nullable(false)->change();
        });
    }
};