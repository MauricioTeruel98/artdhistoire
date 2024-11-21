<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'payment_id', // ID de referencia del pago (Stripe/PayPal)
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Obtiene el usuario que realizó la compra
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la categoría/saga comprada
     */
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    /**
     * Scope para ventas completadas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope para filtrar por moneda
     */
    public function scopeByCurrency($query, $currency)
    {
        return $query->where('currency', strtoupper($currency));
    }
}