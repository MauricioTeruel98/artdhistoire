<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'payment_method', // PayPal o Stripe
        'stripe_subscription_id',
        'paypal_subscription_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Relación con el usuario.
     * Un usuario puede tener varias suscripciones a lo largo del tiempo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica si la suscripción está activa.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->end_date && Carbon::parse($this->end_date)->isFuture();
    }

    /**
     * Mutador para asegurar que start_date se guarde como fecha.
     *
     * @param  string  $value
     * @return void
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value);
    }

    /**
     * Mutador para asegurar que end_date se guarde como fecha.
     *
     * @param  string  $value
     * @return void
     */
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value);
    }
}