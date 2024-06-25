<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\IngredientController;
use App\Http\Controllers\API\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RecipeController;
use App\Http\Controllers\API\SavedRecipeController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [UserController::class, 'logoutUser']);
    Route::get('/user', [UserController::class, 'getUser']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('/recipes', [RecipeController::class, 'index']);
    Route::get('/recipes/{recipe}', [RecipeController::class, 'show']);
    Route::post('/recipes', [RecipeController::class, 'store']);
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update']);
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy']);

    Route::get('categories/{categoryId}/recipes', [RecipeController::class, 'getRecipesByCategory']);

    Route::get('/ingredients', [IngredientController::class, 'index']);

    Route::get('saved_recipes', [SavedRecipeController::class, 'index']);
    Route::post('saved_recipes', [SavedRecipeController::class, 'store']);
    Route::delete('saved_recipes/{id}', [SavedRecipeController::class, 'destroy']);
});

Route::post('/register', [UserController::class, 'registerUser']);
Route::post('/login', [UserController::class, 'loginUser']);

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);
