<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use App\Models\Table;
use App\Models\Used_table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $validator = Validator::make($request->all(), [
            'customer_name' => ['required', 'min:4', 'max:255'],
            'order_type' => ['required', 'numeric'],
            'table_id' => ['nullable', 'numeric']
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'error' => true,
                    'validations' => $validator->errors()
                ], 422);
        }

        $totalItem = 0;
        $totalPrice = 0;

        $orderItems = $request->items;
        if (is_array($orderItems) && count($orderItems) > 0) {
            foreach ($orderItems as $item) {
                $id = $item[0];
                $qty = $item[1];

                $totalItem += $qty;
                $food = Food::find($id);
                $foodPrice = $food->price;

                $subtotal = $qty * $foodPrice;
                $totalPrice += $subtotal;
            }
        }

        $order = new Order();
        $order->waiter_id = $request->user()->id;
        $order->order_number = createOrderNumber($request->user()->id, $request->customer_name, $totalItem, $request->table_id);
        $order->customer_name = $request->customer_name;
        $order->status = 1;
        $order->type = $request->order_type;
        $order->table_id = $request->table_id;
        $order->total_item = $totalItem;
        $order->total_price = $totalPrice;
        $order->save();

        $used_table = new Used_table();
        $used_table->order_id = $order->id;
        $used_table->table_id = $request->table_id;
        $used_table->save();

        if (is_array($orderItems) && count($orderItems) > 0) {
            foreach ($orderItems as $item) {
                $id = $item[0];
                $qty = $item[1];

                $totalItem += $qty;
                $food = Food::find($id);
                $foodPrice = $food->price;

                $food->stock -= $qty;
                $food->save();

                $order->items()->create([
                    'food_id' => $id,
                    'item_qty' => $qty,
                    'item_price' => $foodPrice
                ]);
            }
        }
      
        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil membuat order',
                'order' => $order,
                'table' => $order->table
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
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
}
