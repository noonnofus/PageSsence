<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
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
        $randomizer = new \Random\Randomizer();

        return [
            'title' => $this->faker->words(rand(2, 5), true),
            'author' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'description' => $this->faker->text(200),
            'genre' => $this->faker->randomElement(['Fiction', 'Non-fiction', 'Science Fiction', 'Mystery', 'Biography', 'Historical', 'Poetry', 'Novel', 'Children', 'Drama']),
            'price' => $randomizer->getFloat(10, 30.99),
            'publication_year' => $randomizer->getInt(1990, 2025),
        ];
    }
}
