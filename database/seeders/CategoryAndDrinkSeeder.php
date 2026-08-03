<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Drink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryAndDrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Create Categories
        $coffee = Category::create(['name' => 'Coffee', 'image' => null]);
        $tea = Category::create(['name' => 'Tea', 'image' => null]);
        $frappe = Category::create(['name' => 'Frappe', 'image' => null]);

        //Create Drinks for Coffee
        Drink::create([
            'category_id' => $coffee->id,
            'name' => 'Iced Americano',
            'unit_price' => 2.50,
            'in_stock' => true,
            'image' => null,
        ]);

        Drink::create([
            'category_id' => $coffee->id,
            'name' => 'Iced Latte',
            'unit_price' => 3.00,
            'in_stock' => true,
            'image' => null,
        ]);

        //Create Drinks for Tea
        Drink::create([
            'category_id' => $tea->id,
            'name' => 'Matcha Latte',
            'unit_price' => 3.50,
            'in_stock' => true,
            'image' => null,
        ]);

        Drink::create([
            'category_id' => $tea->id,
            'name' => 'Iced Peach Tea',
            'unit_price' => 2.75,
            'in_stock' => false,
            'image' => null,
        ]);

        //Create Drinks for Frappe
        Drink::create([
            'category_id' => $frappe->id,
            'name' => 'Chocolate Frappe',
            'unit_price' => 4.00,
            'in_stock' => true,
            'image' => null,
        ]);
    }
}
