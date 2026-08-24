<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StCostingItem extends Model
{
    protected $fillable = [
        'st_costing_id',
        'batch_no',
        'species_id',
        'category',
        'volume_ton',
        'log_cost_per_ton',
        'subtotal_cost',
    ];

    public function stCosting(): BelongsTo
    {
        return $this->belongsTo(StCosting::class);
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }
}