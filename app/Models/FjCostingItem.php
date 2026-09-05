<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FjCostingItem extends Model
{
    protected $guarded = [];

    public function fjCosting(): BelongsTo
    {
        return $this->belongsTo(FjCosting::class);
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }
}