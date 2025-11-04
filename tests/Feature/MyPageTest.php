<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MyPageTest extends TestCase
{
    use RefreshDatabase;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
    }

    public function test_customer_can_view_mypage_dashboard(): void
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.mypage.index'));

        $response->assertStatus(200);
        $response->assertViewIs('customer.mypage.index');
    }

    public function test_unauthenticated_user_cannot_access_mypage(): void
    {
        $response = $this->get(route('customer.mypage.index'));

        $response->assertRedirect(route('customer.login'));
    }

    public function test_customer_can_view_reservations_page(): void
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.mypage.reservations'));

        $response->assertStatus(200);
        $response->assertViewIs('customer.mypage.reservations');
    }

    public function test_customer_can_view_their_reservations(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();
        $store = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        $reservation1 = Reservation::factory()->create([
            'customer_id' => $this->customer->id,
            'store_id' => $store->id,
        ]);

        $reservation2 = Reservation::factory()->create([
            'customer_id' => $this->customer->id,
            'store_id' => $store->id,
        ]);

        $otherCustomer = Customer::factory()->create();
        $otherReservation = Reservation::factory()->create([
            'customer_id' => $otherCustomer->id,
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.mypage.reservations'));

        $response->assertStatus(200);
        // Should see own reservations, not other customer's reservations
    }

    public function test_customer_can_view_reviews_page(): void
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.mypage.reviews'));

        $response->assertStatus(200);
        $response->assertViewIs('customer.mypage.reviews');
    }

    public function test_customer_can_view_their_reviews(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();
        $store = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        $review1 = Review::factory()->create([
            'customer_id' => $this->customer->id,
            'store_id' => $store->id,
        ]);

        $review2 = Review::factory()->create([
            'customer_id' => $this->customer->id,
            'store_id' => $store->id,
        ]);

        $otherCustomer = Customer::factory()->create();
        $otherReview = Review::factory()->create([
            'customer_id' => $otherCustomer->id,
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.mypage.reviews'));

        $response->assertStatus(200);
        // Should see own reviews, not other customer's reviews
    }

    public function test_customer_can_view_profile_edit_form(): void
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.mypage.edit-profile'));

        $response->assertStatus(200);
        $response->assertViewIs('customer.mypage.edit-profile');
    }

    public function test_customer_can_update_profile(): void
    {
        $updatedData = [
            'name' => 'Updated Name',
            'nickname' => 'UpdatedNick',
            'email' => 'updated@example.com',
            'phone' => '090-9876-5432',
            'prefecture' => 'Tokyo',
            'city' => 'Shibuya',
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.mypage.update-profile'), $updatedData);

        $response->assertRedirect(route('customer.mypage.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('customers', [
            'id' => $this->customer->id,
            'name' => 'Updated Name',
            'nickname' => 'UpdatedNick',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_customer_profile_update_requires_unique_email(): void
    {
        $otherCustomer = Customer::factory()->create([
            'email' => 'other@example.com',
        ]);

        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'other@example.com',
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.mypage.update-profile'), $updatedData);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_customer_can_keep_same_email_when_updating_profile(): void
    {
        $updatedData = [
            'name' => 'Updated Name',
            'email' => $this->customer->email,
            'nickname' => 'NewNickname',
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.mypage.update-profile'), $updatedData);

        $response->assertRedirect(route('customer.mypage.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('customers', [
            'id' => $this->customer->id,
            'email' => $this->customer->email,
            'name' => 'Updated Name',
        ]);
    }

    public function test_customer_can_view_password_change_form(): void
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.mypage.edit-password'));

        $response->assertStatus(200);
        $response->assertViewIs('customer.mypage.edit-password');
    }

    public function test_customer_can_change_password(): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('old_password'),
        ]);

        $passwordData = [
            'current_password' => 'old_password',
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ];

        $response = $this->actingAs($customer, 'customer')
            ->put(route('customer.mypage.update-password'), $passwordData);

        $response->assertRedirect(route('customer.mypage.index'));
        $response->assertSessionHas('success');

        $customer->refresh();
        $this->assertTrue(Hash::check('new_password123', $customer->password));
    }

    public function test_customer_cannot_change_password_with_wrong_current_password(): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('old_password'),
        ]);

        $passwordData = [
            'current_password' => 'wrong_password',
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ];

        $response = $this->actingAs($customer, 'customer')
            ->put(route('customer.mypage.update-password'), $passwordData);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_password_change_requires_confirmation(): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('old_password'),
        ]);

        $passwordData = [
            'current_password' => 'old_password',
            'password' => 'new_password123',
            'password_confirmation' => 'different_password',
        ];

        $response = $this->actingAs($customer, 'customer')
            ->put(route('customer.mypage.update-password'), $passwordData);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_mypage_displays_recent_reservations(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();
        $store = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        // Create upcoming reservations
        Reservation::factory()->count(3)->create([
            'customer_id' => $this->customer->id,
            'store_id' => $store->id,
            'reservation_date' => now()->addDays(5),
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.mypage.index'));

        $response->assertStatus(200);
    }

    public function test_mypage_displays_recent_reviews(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();
        $store = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        Review::factory()->count(3)->published()->create([
            'customer_id' => $this->customer->id,
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.mypage.index'));

        $response->assertStatus(200);
    }

    public function test_customer_profile_update_validates_required_fields(): void
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.mypage.update-profile'), []);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_customer_profile_update_validates_email_format(): void
    {
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'invalid-email-format',
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.mypage.update-profile'), $updatedData);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_change_requires_all_fields(): void
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.mypage.update-password'), []);

        $response->assertSessionHasErrors(['current_password', 'password']);
    }

    public function test_new_password_must_meet_minimum_length(): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('old_password'),
        ]);

        $passwordData = [
            'current_password' => 'old_password',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->actingAs($customer, 'customer')
            ->put(route('customer.mypage.update-password'), $passwordData);

        $response->assertSessionHasErrors(['password']);
    }
}
