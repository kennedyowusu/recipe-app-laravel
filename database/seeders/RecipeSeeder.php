<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::factory()->count(20)->create()->each(function ($recipe) {
            $ingredients = Ingredient::inRandomOrder()->limit(3)->get();
            $recipe->ingredients()->attach($ingredients->pluck('id'));
        });
    }
}
