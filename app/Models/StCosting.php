<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StCosting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    public function gradeBreakdowns(): HasMany
    {
        return $this->hasMany(StGradeBreakdown::class);
    }

    protected $fillable = [
        'species_id',
        'category',
        'batch_no',
        'log_cost_per_ton',
        'fixed_cost_per_ton',
        'variable_cost_per_ton',
        'manufacturing_cost_per_ton',
        'total_avg_cost_per_ton',
        'has_kd',
        'kd_cost_per_ton',
        'has_cutting',
        'cutting_cost_per_ton',
        'adjusted_cost_per_ton',
        'market_type',
        'target_margin_percentage',
        'benchmark_price_per_ton',
        'actual_selling_price_per_ton',
        'down_value_reason',
        'approval_status',
    ];
    
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StCostingItem::class);
    }
    
}