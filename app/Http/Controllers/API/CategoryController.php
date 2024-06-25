<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $existingCategory = Category::where('name', $request->name)->first();
        if ($existingCategory) {
            return response()->json(['error' => 'Category already exists'], Response::HTTP_CONFLICT);
        }

        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $existingCategory = Category::where('name', $request->name)->first();
        if ($existingCategory && $existingCategory->id !== $category->id) {
            return response()->json(['error' => 'Category name already in use'], Response::HTTP_CONFLICT);
        }

        $category->update($request->validated());
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Request $request)
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $category->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
