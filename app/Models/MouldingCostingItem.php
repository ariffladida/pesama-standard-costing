<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouldingCostingItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mouldingCosting(): BelongsTo
    {
        return $this->belongsTo(MouldingCosting::class);
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }
}