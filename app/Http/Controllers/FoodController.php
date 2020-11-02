<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foods = Food::paginate(16);

        return view('foods.index', compact('foods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return view('foods.create', compact('categories'));
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
            'price' => 'required',
            'description' => 'nullable|max:512',
            'stock' => 'required|min:1|numeric',
            'pictures.*' => 'nullable|max:5096|mimes:jpg,jpeg,png'
        ]);

        $food = new Food();
        $food->name = $request->name;
        $food->price = $request->price;
        $food->description = $request->description;
        $food->stock = $request->stock;
        $food->save();

        $categories = $request->categories;
        if (is_array($categories) && count($categories) > 0) {
            foreach ($categories as $category) {
                $food->categories()->attach($category);
            }
        }

        if ($request->has('pictures')) {
            $food->addMultipleMediaFromRequest(['pictures'])
                ->each(function ($file) {
                    $file->toMediaCollection('food_pictures');
                });
        }

        return redirect()
            ->back()
            ->withSuccess('Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        return view('foods.show', compact('food'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function edit(Food $food)
    {
        $categories = Category::all();
        $foodCategories = [];

        foreach ($food->categories as $item) {
            $foodCategories[] = $item->id;
        }

        return view('foods.edit', compact('categories', 'food', 'foodCategories'));
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
        $request->validate([
            'name' => 'required|min:4|max:255',
            'price' => 'required',
            'description' => 'nullable|max:512',
            'stock' => 'required|min:1|numeric',
            'pictures.*' => 'nullable|max:5096|mimes:jpg,jpeg,png'
        ]);

        $food->name = $request->name;
        $food->price = $request->price;
        $food->description = $request->description;
        $food->stock = $request->stock;
        $food->save();

        $categories = $request->categories;
        if (is_array($categories) && count($categories) > 0) {
            $food->categories()->detach();

            foreach ($categories as $category) {
                $food->categories()->attach($category);
            }
        }

        $deleteMedia = $request->delete_media;
        if (is_array($deleteMedia) && count($deleteMedia) > 0) {
            foreach ($deleteMedia as $mediaID) {
                $food->deleteMedia($mediaID);
            }
        }

        if ($request->has('pictures')) {
            $food->addMultipleMediaFromRequest(['pictures'])
                ->each(function ($file) {
                    $file->toMediaCollection('food_pictures');
                });
        }

        return redirect()
            ->back()
            ->withSuccess('Berhasil memperbarui data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        if (is_array($food->media) && count($food->media) > 0) {
            foreach ($food->media as $media) {
                $media->delete();
            }
        }

        if (is_array($food->categories) && count($food->categories) > 0) {
            $food->categories()->detach();
        }

        $food->delete();

        return redirect()
            ->route('admin.foods.index')
            ->withSuccess('Berhasil menghapus data makanan');
    }

    public function stock()
    {
        $foods = Food::paginate(16);

        return view('foods.stock', compact('foods'));
    }
}
