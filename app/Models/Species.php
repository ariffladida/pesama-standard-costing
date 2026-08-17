<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Species extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stCostings(): HasMany
    {
        return $this->hasMany(StCosting::class);
    }
}