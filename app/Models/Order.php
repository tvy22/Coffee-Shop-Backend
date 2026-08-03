<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_type',
        'status',
        'total',
        'order_date',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'order_date' => 'datetime',
    ];

    //Relationship: An Order belongs to a Staff user.
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    //Relationship: An Order has many OrderDetails.
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
