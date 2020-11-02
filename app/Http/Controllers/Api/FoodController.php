<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food)
    {
        $section = $request->section;
        switch ($section) {
            case 'stock' :
                $validator = Validator::make($request->all(), [
                    'stock' => ['required', 'numeric'],
                    'isAvailable' => ['required', 'boolean']
                ]);

                if ($validator->fails()) {
                    return response()
                        ->json([
                            'error' => true,
                            'validations' => $validator->errors()
                        ], 422);
                }

                $food->stock = $request->stock;
                $food->is_available = $request->is_available;
                $food->save();

                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Berhasil memperbarui data stok',
                        'food' => $food
                    ]);
            break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        //
    }

    public function stock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stock.*' => ['required', 'numeric'],
            'is_available.*' => ['required', 'boolean']
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'error' => true,
                    'validations' => $validator->errors()
                ], 422);
        }

        $stock = $request->stock;
        $is_available = $request->is_available;

        if (is_array($stock) && count($stock) > 0) {
            Food::find(collect($stock)->pluck('id')->toArray())->map(function ($item, $key) use ($stock) {
                $item['stock'] = $stock[$key]['stock'];
                return $item->save();
            });
        }

        if (is_array($is_available) && count($is_available) > 0) {
            Food::find(collect($is_available)->pluck('id')->toArray())->map(function ($item, $key) use ($is_available) {
                $item['is_available'] = $is_available[$key]['is_available'];
                return $item->save();
            });
        }

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil memperbarui data stok',
            ]);
    }
}
