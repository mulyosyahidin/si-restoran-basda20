<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $category->pictureUrl = isset($category->media[0]) ? $category->media[0]->getFullUrl() : null;
        }

        return ['data' => $categories];
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
            'name' => ['required', 'min:4', 'max:255'],
            'description' => ['nullable', 'min:10', 'max:512'],
            'picture' => ['nullable', 'max:5096', 'mimes:jpg,jpeg,png,svg']
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'error' => true,
                    'validations' => $validator->errors()
                ], 422);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        if ($request->has('picture') && $request->file('picture')->isValid()) {
            $category->addMediaFromRequest('picture')
                ->toMediaCollection('category_pictures');
        }

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil menambah data kategori'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:4', 'max:255'],
            'description' => ['nullable', 'min:10', 'max:512'],
            'picture' => ['nullable', 'max:5096', 'mimes:jpg,jpeg,png,svg']
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'error' => true,
                    'validations' => $validator->errors()
                ], 422);
        }

        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        if ($request->has('picture') && $request->file('picture')->isValid()) {
            if (isset($category->media[0])) {
                $category->media[0]->delete();
            }

            $category->addMediaFromRequest('picture')
                ->toMediaCollection('category_pictures');
        }

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil memperbarui data kategori'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (isset($category->media[0])) {
            $category->media[0]->delete();
        }

        $category->delete();

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil menghapus kategori'
            ]);
    }
}
