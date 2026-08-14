<?php

namespace Database\Factories;

use App\Models\Drink;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $drink = Drink::inRandomOrder()->first();
        $quantity = $this->faker->numberBetween(1,3);

        return [
            'drink_id' => $drink->id,
            'quantity' => $quantity,
            'amount'   => $drink->unit_price * $quantity
        ];
    }
}
