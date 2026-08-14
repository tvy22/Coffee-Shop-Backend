<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'order_type' => $this->faker->randomElement(['dine_in', 'takeaway']),
            'status' => $this->faker->randomElement(['pending', 'preparing', 'completed', 'cancelled']),
            'total' => 0,
            'order_date' => now()
        ];
    }
}
