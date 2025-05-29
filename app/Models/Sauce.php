<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sauce extends Model
{
    protected $fillable = ['name', 'extra_price'];

    public function orderSauces()
    {
        return $this->hasMany(OrderSauce::class);
    }
}
