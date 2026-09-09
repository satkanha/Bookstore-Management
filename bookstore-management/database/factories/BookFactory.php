<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(fake()->numberBetween(2, 5));

        return [
            'category_id' => Category::factory(),
            'author_id' => Author::factory(),
            'title' => Str::headline($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1000, 999999),
            'isbn' => fake()->unique()->numerify('978##########'),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->randomFloat(2, 8, 95),
            'stock' => fake()->numberBetween(0, 80),
            'cover_image' => null,
            'publication_date' => fake()->dateTimeBetween('-20 years', '-1 month')->format('Y-m-d'),
            'status' => true,
            'featured' => fake()->boolean(25),
        ];
    }
}
