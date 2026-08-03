<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'unit_price',
        'in_stock',
        'image',
    ];

    protected $casts = [
        'in_stock' => 'boolean',
        'unit_price' => 'decimal:2',
    ];

    //Relationship: A Drink belongs to a Category.
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //Relationship: A Drink can appear in many OrderDetails.
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
