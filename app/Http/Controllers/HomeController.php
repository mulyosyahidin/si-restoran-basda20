<?php

namespace App\Http\Controllers;

use App\Models\Food;
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

        return view('home', compact('foods', 'tables', 'used_tables'));
    }
}
