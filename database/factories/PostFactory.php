<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();
        return [
            'category_id' => Category::query()->inRandomOrder()->first()->id,
            'author_id' => Author::query()->inRandomOrder()->first()->id,
            'title' => $title,
            'content' => fake()->paragraph(5),
            'published_at' => fake()->optional()->dateTime()
        ];
    }
}
