<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessCoupon extends Model
{
    protected $fillable = [
        'code',
        'title',
        'category_id',
        'duration_days',
        'is_used',
        'used_at',
        'used_by_user_id',
        'max_uses',
        'used_count'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'used_by_user_id');
    }
}