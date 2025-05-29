<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSauce extends Model
{
    protected $fillable = ['order_detail_id', 'sauce_id', 'is_free'];

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function sauce()
    {
        return $this->belongsTo(Sauce::class);
    }
}
