<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
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
            'author_id' => User::query()->inRandomOrder()->first()->id,
            'title' => $title,
            'content' => fake()->paragraph(5),
            'published_at' => fake()->dateTimeBetween('-1 year', \Illuminate\Support\now()),
        ];
    }
}
