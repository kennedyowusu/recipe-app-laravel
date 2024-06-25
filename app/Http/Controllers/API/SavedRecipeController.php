<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SavedRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedRecipeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $savedRecipes = SavedRecipe::where('user_id', $user->id)->with('recipe')->get();
        return response()->json($savedRecipes);
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
        ]);

        // Check if the recipe is already saved by the user
        $existingSavedRecipe = SavedRecipe::where('user_id', $user->id)
            ->where('recipe_id', $request->recipe_id)
            ->first();

        if ($existingSavedRecipe) {
            return response()->json(['message' => 'Recipe is already saved'], 409);
        }

        $savedRecipe = SavedRecipe::create([
            'user_id' => $user->id,
            'recipe_id' => $request->recipe_id,
        ]);

        return response()->json($savedRecipe, 201);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $savedRecipe = SavedRecipe::where('user_id', $user->id)->where('recipe_id', $id)->firstOrFail();
        $savedRecipe->delete();

        return response()->json(null, 200);
    }
}
