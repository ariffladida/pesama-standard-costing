<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StCostingItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stCosting(): BelongsTo
    {
        return $this->belongsTo(StCosting::class);
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }
}