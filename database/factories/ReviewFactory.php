<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Store;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'customer_id' => Customer::factory(),
            'rating' => fake()->randomFloat(1, 1, 5),
            'title' => fake()->sentence(),
            'content' => fake()->realText(300),
            'visit_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'status' => 'pending',
            'published_at' => null,
        ];
    }

    /**
     * Indicate that the review is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the review is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    /**
     * Indicate that the review has a high rating.
     */
    public function highRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->randomFloat(1, 4, 5),
        ]);
    }
}
