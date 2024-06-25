<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RecipeResource::collection(Recipe::with('category', 'ingredients')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecipeRequest $request)
    {
        $validatedAttributes = $request->validated();

        if (!$request->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $existingRecipe = Recipe::where('name', $validatedAttributes['name'])->first();
        if ($existingRecipe) {
            return response()->json(['error' => 'Recipe already exists'], Response::HTTP_CONFLICT);
        }

        $recipe = Recipe::create($validatedAttributes);
        $recipe->ingredients()->attach($validatedAttributes['ingredients']);

        return new RecipeResource($recipe->load('category', 'ingredients'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        return new RecipeResource($recipe->load('category', 'ingredients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {

        if (!$request->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $validatedAttributes = $request->validated();

        $existingRecipe = Recipe::where('name', $validatedAttributes['name'])->first();
        if ($existingRecipe && $existingRecipe->id !== $recipe->id) {
            return response()->json(['error' => 'Recipe name already in use'], Response::HTTP_CONFLICT);
        }

        $recipe->update($validatedAttributes);
        $recipe->ingredients()->sync($validatedAttributes['ingredients']);

        return new RecipeResource($recipe->load('category', 'ingredients'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe, Request $request)
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $recipe->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function getRecipesByCategory($categoryId)
    {
        return RecipeResource::collection(Recipe::where('category_id', $categoryId)->
        with('category')->get());
    }
}
