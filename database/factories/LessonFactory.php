<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LessonFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'module_id' => Module::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(2, true),
            'type' => 'video',
            'duration_minutes' => fake()->numberBetween(5, 45),
            'sort_order' => 0,
            'is_published' => false,
            'is_free_preview' => false,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'is_published' => true,
        ]);
    }

    public function freePreview(): static
    {
        return $this->state(fn () => [
            'is_free_preview' => true,
        ]);
    }
}
