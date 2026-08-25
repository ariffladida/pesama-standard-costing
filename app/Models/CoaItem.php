<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoaItem extends Model
{
    protected $fillable = [
        'coa_code',
        'name',
        'cost_type',
        'basis_type',
        'standard_rate_per_ton',
        'is_reducible',
    ];
}