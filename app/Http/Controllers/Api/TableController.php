<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
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
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(Table $table)
    {
        return $table;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Table $table)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:4', 'max:255'],
            'seat_number' => ['nullable', 'numeric'],
            'description' => ['nullable', 'max:255'],
            'picture' => ['nullable', 'max:5096', 'mimes:jpg,jpeg,png']
        ]);

        if ($validator->fails()) {
            return response()   
                ->json([
                    'error' => true,
                    'validations' => $validator->errors()
                ], 422);
        }

        $table->name = $request->name;
        $table->seat_number = $request->seat_number;
        $table->description = $request->description;
        $table->save();

        if ($request->has('picture') && $request->file('picture')->isValid()) {
            if (isset($table->media[0])) {
                $table->media[0]->delete();
            }

            $table->addMediaFromRequest('picture')
                ->toMediaCollection('table_pictures');
        }

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil menyimpan data meja'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(Table $table)
    {
        if (isset($table->media[0])) {
            $table->media[0]->delete();
        }

        $table->delete();

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil menghapus meja'
            ]);
    }
}
