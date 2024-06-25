<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Beverages'],
            ['name' => 'Bakery'],
            ['name' => 'Canned Goods'],
            ['name' => 'Dairy'],
            ['name' => 'Baking Goods'],
            ['name' => 'Frozen Foods'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
