<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //Dashboard Overview
    public function dashboardOverview(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $baseQuery = Order::where('status', 'completed')
            ->when($startDate, fn($q) => $q->whereDate('order_date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('order_date', '<=', $endDate));

        // 1. Summary Cards(KPIs): total revenue, total orders count, total cancelled orders count, average order value
        $totalRevenue = (float) (clone $baseQuery)->sum('total');
        $completedOrdersCount = (clone $baseQuery)->count();
        $cancelledOrdersCount = Order::where('status', 'cancelled')
            ->when($startDate, fn($q) => $q->whereDate('order_date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('order_date', '<=', $endDate))
            ->count();

        $averageOrderValue = $completedOrdersCount > 0
            ? round($totalRevenue / $completedOrdersCount, 2) // Fixed: added , 2
            : 0;

        // 2. Sales Breakdown by Order Type (dine-in, takeaway)
        $orderTypeBreakdown = (clone $baseQuery)
            ->select('order_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('order_type')
            ->get();

        // 3. Top 5 Best-Selling Drinks
        $topSellingDrinks = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('drinks', 'order_details.drink_id', '=', 'drinks.id')
            ->where('orders.status', 'completed') // Fixed: statue -> status
            ->when($startDate, fn($q) => $q->whereDate('orders.order_date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('orders.order_date', '<=', $endDate))
            ->select(
                'drinks.id',
                'drinks.name',
                DB::raw('SUM(order_details.quantity) as total_quantity_sold'),
                DB::raw('SUM(order_details.amount) as total_revenue_generated')
            )
            ->groupBy('drinks.id', 'drinks.name')
            ->orderByDesc('total_quantity_sold')
            ->limit(5)
            ->get();

        return response()->json([
            'period' => [
                'start_date' => $startDate ?? 'All time',
                'end_date'   => $endDate ?? 'All time',
            ],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'completed_orders_count' => $completedOrdersCount,
                'cancelled_orders_count' => $cancelledOrdersCount,
                'average_order_value' => $averageOrderValue, 
            ],
            'order_type_breakdown' => $orderTypeBreakdown,
            'top_selling_drinks' => $topSellingDrinks
        ]);
    }

    //Revenue trends for charts
    public function salesTrend(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $dailySales = Order::where('status', 'completed')
            ->when($startDate, fn($q) => $q->whereDate('order_date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('order_date', '<=', $endDate))
            ->select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as daily_revenue')
            )
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy(DB::raw('DATE(order_date)'), 'ASC')
            ->get();

        return response()->json([
            'daily_sales_trend' => $dailySales,
        ]);
    }
}
