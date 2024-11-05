<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'title',
        'discount_percentage',
        'used'
    ];

    protected $casts = [
        'used' => 'boolean'
    ];
}