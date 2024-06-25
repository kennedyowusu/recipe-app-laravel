<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'cuisine' => $this->faker->word,
            'cook_time_minutes' => $this->faker->numberBetween(10, 120),
            // 'category_id' => $this->faker->numberBetween(1, 6),
            'category_id' => Category::factory(),
            'is_trending' => $this->faker->boolean(20),
            'is_latest' => $this->faker->boolean(20),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Recipe $recipe) {
            $ingredients = Ingredient::inRandomOrder()->limit(3)->get();
            $recipe->ingredients()->sync($ingredients->pluck('id'));
        });
    }
}
