<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\Table;
use App\Models\Used_table;
use Illuminate\Http\Request;

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
        $used_tables_get = Used_table::all();
        $used_tables = [];

        foreach ($used_tables_get as $table) {
            $used_tables[] = $table->table_id;
        }

        $orders = Order::where('status', 1)->get();
        $readyOrders = Order::where('status', 2)->get();

        $orderCount['on_process'] = Order::where('status', 1)->count();
        $orderCount['ready'] = Order::where('status', 2)->count();

        return view('home', compact('foods', 'orderCount', 'orders', 'readyOrders', 'tables', 'used_tables'));
    }
}
