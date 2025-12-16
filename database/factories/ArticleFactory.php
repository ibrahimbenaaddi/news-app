<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = ['Technology','Business','Health','Sports','Entertainment','Environment'] ;
        $key = random_int(0,count($category)-1);
        return [
            'title' => fake()->sentence,
            'body' => fake()->paragraphs(10,true),
            'category' => $category[$key]
        ];
    }
}
