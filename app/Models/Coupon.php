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
        'is_active'
    ];

    protected $casts = [
        'used' => 'boolean'
    ];
}