<?php

namespace App\Models;

class SaleLock extends Model
{
    protected $fillable = [
        'cashier_ulid',
        'products',
    ];

    protected $casts = [
        'products' => 'json',
    ];

    /**
     * Get the cashier associated with the sale.
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_ulid', 'ulid');
    }
}
