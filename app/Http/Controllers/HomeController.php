<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Table;
use App\Models\Used_table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $foods = Food::all();
        $tables = Table::all();
        $used_tables_get = Used_table::join('orders', 'orders.id', 'used_tables.order_id')->where('orders.status', 1)->get();
        $used_tables = [];

        foreach ($used_tables_get as $table) {
            $used_tables[] = $table->table_id;
        }

        $orders = Order::where('status', 1)->get();
        $readyOrders = Order::where('status', 2)->get();

        $orderCount['onProcess'] = Order::where('status', 1)->count();
        $orderCount['ready'] = Order::where('status', 2)->count();

        $stats['foods'] = Food::count();
        $stats['orders'] = Order::count();
        $stats['income'] = Order::income();
        $stats['todayIncome'] = Order::todayIncome();
        $stats['todayOrder'] = Order::whereDate('created_at', Carbon::today())->count();
        $stats['todayIncome'] = Order::where('status', 3)->whereDate('created_at', Carbon::today())->sum('total_price');
        $stats['weekOrder'] = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $stats['weekIncome'] = Order::where('status', 3)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total_price');
        $stats['orderStats'] = Order::weekStats();

        $stats['mostProducts'] = Order_item::select(DB::raw('food_id, count(food_id) as order_count'))->groupBy('food_id')->orderBy('order_count', 'desc')->take(10)->get();

        return view('home', compact('foods', 'orderCount', 'orders', 'readyOrders', 'tables', 'used_tables', 'stats'));
    }
}
