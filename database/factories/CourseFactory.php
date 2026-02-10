<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'instructor_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(15),
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'price' => fake()->randomFloat(2, 29, 499),
            'currency' => 'USD',
            'status' => 'draft',
            'duration_hours' => fake()->numberBetween(5, 60),
            'sort_order' => 0,
            'is_featured' => false,
            'is_free' => false,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn () => [
            'is_featured' => true,
        ]);
    }

    public function free(): static
    {
        return $this->state(fn () => [
            'is_free' => true,
            'price' => 0,
        ]);
    }
}
