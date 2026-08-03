<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Drink;
use Illuminate\Database\Seeder;

class CategoryAndDrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Categories
        $coffee = Category::create([
            'name' => 'Coffee',
            'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=500',
        ]);

        $tea = Category::create([
            'name' => 'Tea',
            'image' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=500',
        ]);

        $frappe = Category::create([
            'name' => 'Frappe',
            'image' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500',
        ]);

        // 2. Create Drinks for Coffee
        Drink::create([
            'category_id' => $coffee->id,
            'name' => 'Iced Americano',
            'unit_price' => 2.50,
            'in_stock' => true,
            'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=500',
        ]);

        Drink::create([
            'category_id' => $coffee->id,
            'name' => 'Iced Latte',
            'unit_price' => 3.00,
            'in_stock' => true,
            'image' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=500',
        ]);

        // 3. Create Drinks for Tea
        Drink::create([
            'category_id' => $tea->id,
            'name' => 'Matcha Latte',
            'unit_price' => 3.50,
            'in_stock' => true,
            'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=500',
        ]);

        Drink::create([
            'category_id' => $tea->id,
            'name' => 'Iced Peach Tea',
            'unit_price' => 2.75,
            'in_stock' => false,
            'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500',
        ]);

        // 4. Create Drinks for Frappe
        Drink::create([
            'category_id' => $frappe->id,
            'name' => 'Chocolate Frappe',
            'unit_price' => 4.00,
            'in_stock' => true,
            'image' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500',
        ]);
    }
}
