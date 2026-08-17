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
}