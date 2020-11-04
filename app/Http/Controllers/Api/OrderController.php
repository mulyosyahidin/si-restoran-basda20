<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use App\Models\Used_table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;

class OrderController extends Controller
{
    public $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(env('PUSHER_APP_KEY'), env(('PUSHER_APP_SECRET')), env('PUSHER_APP_ID'), array('cluster' => env('PUSHER_APP_CLUSTER')));
    }

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
            'table_id' => ['nullable', 'numeric'],
            'note' => ['nullable', 'max:1024']
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
        $order->note = $request->note;
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

        $orderItems = [];
        $n = 0;
        foreach ($order->items as $food) {
            $orderItems[$n]['name'] = $food->food->name;
            $orderItems[$n]['qty'] = $food->item_qty;

            $n++;
        }

        $order->time = Carbon::parse($order->created_at)->format('H:i');
        $order->time_update = Carbon::parse($order->updated_at)->format('H:i');

        $notify = array(
            'order' => $order,
            'items' => $orderItems,
            'table' => $order->table
        );

        $this->pusher->trigger('restoran19', 'notifyKitchenNewOrder', $notify);

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil membuat order',
                'order' => $order,
                'table' => $order->table,
                'used_tables' => Used_table::all()
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
        $validator = Validator::make($request->all(), [
            'section' => ['required']
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'error' => true,
                    'validations' => $validator->errors()
                ]);
        }

        $section = $request->section;
        switch ($section) {
            case 'mark_as_ready':
                $order->status = 2;
                $order->save();

                $order->update_time = Carbon::parse($order->updated_at)->format('H:i');

                $orderCount['on_process'] = Order::where('status', 1)->count();
                $orderCount['ready'] = Order::where('status', 2)->count();
                $orderCount['order'] = $order;
                $orderCount['table'] = $order->table;

                $this->pusher->trigger('restoran19', 'updateWaiterOrderCount', $orderCount);

                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Order ditandai telah siap',
                        'orderCount' => $orderCount['on_process']
            ]);
            break;
        }
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

    public function make_payment(Request $request, Order $order)
    {
        $order->status = 3;
        $order->save();

        Used_table::where('order_id', $order->id)->delete();

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil melakukan pembayaran order'
            ]);
    }
}
