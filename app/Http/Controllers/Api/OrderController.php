<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Drink;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1.Get all orders (for Staff Dashboard)

    public function index(Request $request)
    {
        $orders = Order::with(['user', 'orderDetails.drink'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return response()->json($orders);
    }

    //2. Create a new Order (checkout)
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated, $request) {
            $totalAmount = 0;
            $orderDetailsData = [];

            $drinkIds = collect($validated['items'])->pluch('drink_id');
            $drinks = Drink::whereIn('id', $drinkIds)->get()->keyBy('id');

            foreach($validated['items'] as $item) {
                $drink = $drinks[$item['drink_id']];
                $subtotal = $drink->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderDetailsData[] = [
                    'drink_id' => $drink->id,
                    'quantity' => $item['quantity'],
                    'amount'   => $subtotal,
                ];
            }

            // Create Order
            $order = Order::create([
                'user_id' => $request->user()?->id,
                'order_type' => $validated['order_type'],
                'status' => 'pending',
                'total' => $totalAmount,
                'order_date' => now(),
            ]);

            // Create Order Details
            $order->orderDetails()->createMany($orderDetailsData);

            return $order;
        });

        return response()->json([
            'message' => 'Order placed successfully!',
            'data' => $order->load('orderDetails.drink'),
        ], 201);
    }

    // 3. Update Order status (Pending -> Preparing -> Completed)
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validated([
            'status' => 'required|in:pending,preparing,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Order status updated successfully!',
            'data' => $order,
        ]);
    }
}
