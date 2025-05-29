<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['order_id', 'dumpling_id', 'quantity'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function dumpling()
    {
        return $this->belongsTo(Dumpling::class);
    }

    public function sauces()
    {
        return $this->hasMany(OrderSauce::class);
    }
}
