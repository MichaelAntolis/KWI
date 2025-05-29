<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'total_price', 'payment_method'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Method untuk mendapatkan label payment method
    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'transfer' => 'Transfer Bank'
        ];

        return $labels[$this->payment_method] ?? 'Tunai';
    }

    // Method untuk mendapatkan icon payment method
    public function getPaymentMethodIconAttribute()
    {
        $icons = [
            'cash' => 'bi-cash-stack',
            'qris' => 'bi-qr-code',
            'transfer' => 'bi-bank'
        ];

        return $icons[$this->payment_method] ?? 'bi-cash-stack';
    }
}
