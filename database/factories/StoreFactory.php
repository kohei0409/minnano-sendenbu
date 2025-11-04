<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\Category;
use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_name' => fake()->company(),
            'industry' => fake()->randomElement(['飲食', '小売', 'サービス', '美容', '医療']),
            'contact_name' => fake()->name(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'description' => fake()->realText(200),
            'status' => 'active',
            'category_id' => Category::factory(),
            'area_id' => Area::factory(),
            'postal_code' => fake()->postcode(),
            'prefecture' => fake()->prefecture(),
            'city' => fake()->city(),
            'street_address' => fake()->streetAddress(),
            'building' => fake()->optional()->secondaryAddress(),
            'latitude' => fake()->latitude(35, 36),
            'longitude' => fake()->longitude(139, 140),
            'average_rating' => 0,
            'review_count' => 0,
            'view_count' => 0,
            'favorite_count' => 0,
            'is_featured' => false,
            'featured_until' => null,
        ];
    }

    /**
     * Indicate that the store is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the store is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    /**
     * Indicate that the store is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'featured_until' => now()->addMonth(),
        ]);
    }

    /**
     * Indicate that the store has reviews.
     */
    public function withReviews(int $count = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'review_count' => $count,
            'average_rating' => fake()->randomFloat(2, 3, 5),
        ]);
    }
}
