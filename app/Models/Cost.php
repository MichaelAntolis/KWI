<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cost extends Model
{
    protected $fillable = [
        'user_id',
        'item_name',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'description',
        'purchased_date'
    ];

    protected $casts = [
        'purchased_date' => 'date',
        'quantity' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk format tanggal pembelian
    public function getPurchasedDateFormattedAttribute()
    {
        return $this->purchased_date->format('d F Y');
    }

    // Accessor untuk format quantity dengan unit
    public function getQuantityWithUnitAttribute()
    {
        return $this->quantity . ' ' . $this->unit;
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeWhereDateBetween($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('purchased_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            return $query->where('purchased_date', '>=', $startDate);
        } elseif ($endDate) {
            return $query->where('purchased_date', '<=', $endDate);
        }

        return $query;
    }

    // Scope untuk filter berdasarkan user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
