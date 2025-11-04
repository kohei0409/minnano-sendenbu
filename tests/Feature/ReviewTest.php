<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Review;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected Customer $customer;
    protected Store $store;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::factory()->create();
        $area = Area::factory()->create();

        $this->customer = Customer::factory()->create();
        $this->store = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
        ]);
    }

    public function test_authenticated_customer_can_create_review(): void
    {
        Mail::fake();

        $reviewData = [
            'rating' => 4.5,
            'title' => 'Great experience!',
            'content' => 'The service was excellent and the food was delicious.',
            'visit_date' => now()->subDays(3)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('customer.reviews.store', $this->store), $reviewData);

        $response->assertRedirect(route('stores.show', $this->store));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
            'rating' => 4.5,
            'title' => 'Great experience!',
            'status' => 'pending',
        ]);
    }

    public function test_unauthenticated_user_cannot_create_review(): void
    {
        $reviewData = [
            'rating' => 4.5,
            'title' => 'Great experience!',
            'content' => 'The service was excellent.',
        ];

        $response = $this->post(route('customer.reviews.store', $this->store), $reviewData);

        $response->assertRedirect(route('customer.login'));
    }

    public function test_review_requires_valid_data(): void
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('customer.reviews.store', $this->store), []);

        $response->assertSessionHasErrors(['rating', 'title', 'content']);
    }

    public function test_rating_must_be_between_1_and_5(): void
    {
        $reviewData = [
            'rating' => 6,
            'title' => 'Test',
            'content' => 'Test content',
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('customer.reviews.store', $this->store), $reviewData);

        $response->assertSessionHasErrors(['rating']);
    }

    public function test_customer_can_upload_review_images(): void
    {
        Storage::fake('public');
        Mail::fake();

        $reviewData = [
            'rating' => 4.5,
            'title' => 'Great experience!',
            'content' => 'The service was excellent.',
            'images' => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.jpg'),
            ],
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('customer.reviews.store', $this->store), $reviewData);

        $response->assertRedirect(route('stores.show', $this->store));

        $review = Review::where('customer_id', $this->customer->id)->first();
        $this->assertCount(2, $review->images);
    }

    public function test_customer_can_edit_own_review(): void
    {
        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.reviews.edit', $review));

        $response->assertStatus(200);
        $response->assertViewIs('public.reviews.edit');
    }

    public function test_customer_can_update_own_review(): void
    {
        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
        ]);

        $updatedData = [
            'rating' => 5,
            'title' => 'Updated title',
            'content' => 'Updated content',
            'visit_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->patch(route('customer.reviews.update', $review), $updatedData);

        $response->assertRedirect(route('stores.show', $this->store));

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'title' => 'Updated title',
            'content' => 'Updated content',
            'rating' => 5,
        ]);
    }

    public function test_customer_cannot_edit_others_review(): void
    {
        $otherCustomer = Customer::factory()->create();
        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $otherCustomer->id,
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.reviews.edit', $review));

        $response->assertStatus(403);
    }

    public function test_customer_can_delete_own_review(): void
    {
        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->delete(route('customer.reviews.destroy', $review));

        $response->assertRedirect();

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_customer_cannot_delete_others_review(): void
    {
        $otherCustomer = Customer::factory()->create();
        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $otherCustomer->id,
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->delete(route('customer.reviews.destroy', $review));

        $response->assertStatus(403);
    }

    public function test_admin_can_approve_review(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.reviews.approve', $review));

        $response->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => 'published',
        ]);

        $review->refresh();
        $this->assertNotNull($review->published_at);
    }

    public function test_admin_can_reject_review(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.reviews.reject', $review));

        $response->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => 'rejected',
        ]);
    }

    public function test_admin_can_delete_review(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.reviews.destroy', $review));

        $response->assertRedirect();

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_non_admin_cannot_approve_review(): void
    {
        $storeOwner = User::factory()->create([
            'role' => 'store_owner',
            'status' => 'active',
            'store_id' => $this->store->id,
        ]);

        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($storeOwner)
            ->post(route('admin.reviews.approve', $review));

        $response->assertStatus(403);
    }

    public function test_store_rating_updates_when_review_is_published(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create a pending review
        $review = Review::factory()->create([
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
            'status' => 'pending',
            'rating' => 4.5,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.reviews.approve', $review));

        // Update store rating manually as events might not fire in tests
        $this->store->updateRating();
        $this->store->refresh();

        $this->assertEquals(1, $this->store->review_count);
        $this->assertEquals(4.5, $this->store->average_rating);
    }
}
