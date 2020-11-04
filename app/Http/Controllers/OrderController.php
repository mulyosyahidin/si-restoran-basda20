<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::paginate();
        $title = 'Semua Order';

        return view('orders.index', compact('orders', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function print(Order $order)
    {
        return view('orders.print', compact('order'));
    }

    public function queue()
    {
        $orders = Order::where('status', 1)->paginate();
        $title = 'Order Dalam Antrian Dapur';

        return view('orders.index', compact('orders', 'title'));
    }

    public function ready()
    {
        $orders = Order::where('status', 2)->paginate();
        $title = 'Order Disajikan';

        return view('orders.index', compact('orders', 'title'));
    }

    public function finish()
    {
        $orders = Order::where('status', 3)->paginate();
        $title = 'Order Selesai';

        return view('orders.index', compact('orders', 'title'));
    }
}
