<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'title',
        'discount_percentage',
        'used',
        'is_dateable',
        'limit_date',
        'is_active',
        'max_uses',
        'current_uses'
    ];

    protected $casts = [
        'used' => 'boolean',
        'is_dateable' => 'boolean',
        'limit_date' => 'datetime',
        'is_active' => 'boolean',
        'max_uses' => 'integer',
        'current_uses' => 'integer'
    ];
}