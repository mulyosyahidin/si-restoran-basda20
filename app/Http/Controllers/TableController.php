<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tables = Table::paginate(20);

        return view('tables.index', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tables.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:4|max:255',
            'seat_number' => 'nullable|numeric',
            'description' => 'nullable|max:255',
            'picture' => 'nullable|max:5096|mimes:jpg,jpeg,png'
        ]);

        $table = new Table();
        $table->name = $request->name;
        $table->seat_number = $request->seat_number;
        $table->description = $request->description;
        $table->save();

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $table->addMediaFromRequest('picture')
                ->toMediaCollection('table_pictures');
        }

        return redirect()
            ->back()
            ->withSuccess('Berhasil menambahkan meja');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(Table $table)
    {
        return view('tables.show', compact('table'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function edit(Table $table)
    {
        //
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
        //
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

        return redirect()
            ->route('admin.tables.index')
            ->withSuccess('Berhasil menghapus meja');
    }
}
