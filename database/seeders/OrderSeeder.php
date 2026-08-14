<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory(10)->create()->each(function ($order) {
            $details = OrderDetail::factory(rand(1,3))->make();
            $order->orderDetails()->saveMany($details);

            $order->update([
                'total' => $order->orderDetails()->sum('amount')
            ]);
        });
    }
}
