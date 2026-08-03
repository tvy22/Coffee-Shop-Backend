<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'drink_id',
        'quantity',
        'amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    //Relationship: An OrderDetail belongs to an Order.
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    //Relationship: An OrderDetail belongs to a Drink.
    public function drink()
    {
        return $this->belongsTo(Drink::class);
    }
}
