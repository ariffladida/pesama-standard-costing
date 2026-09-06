<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoaItem extends Model
{
    use HasFactory;

    protected $table = 'coa_items';

    protected $guarded = [];

    protected $casts = [
        'standard_rate_per_ton' => 'decimal:2',
    ];
}