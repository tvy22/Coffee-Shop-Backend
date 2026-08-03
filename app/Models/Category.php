<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'image',
    ];

    //Relationship: A category has many Drinks.
    public function drinks()
    {
        return $this->hasMany(Drink::class);
    }
}
