<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    protected Customer $customer;
    protected Store $store;
    protected User $storeOwner;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::factory()->create();
        $area = Area::factory()->create();

        $this->store = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        $this->customer = Customer::factory()->create();

        $this->storeOwner = User::factory()->create([
            'role' => 'store_owner',
            'status' => 'active',
            'store_id' => $this->store->id,
        ]);
    }

    public function test_guest_can_create_reservation(): void
    {
        Mail::fake();

        $reservationData = [
            'reservation_date' => now()->addDays(3)->format('Y-m-d'),
            'reservation_time' => '18:00',
            'number_of_people' => 4,
            'customer_name' => 'John Doe',
            'customer_phone' => '090-1234-5678',
            'customer_email' => 'john@example.com',
            'message' => 'Window seat please',
        ];

        $response = $this->post(route('reservations.store', $this->store), $reservationData);

        $response->assertRedirect(route('stores.show', $this->store));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reservations', [
            'store_id' => $this->store->id,
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_authenticated_customer_can_create_reservation(): void
    {
        Mail::fake();

        $reservationData = [
            'reservation_date' => now()->addDays(3)->format('Y-m-d'),
            'reservation_time' => '18:00',
            'number_of_people' => 4,
            'customer_name' => $this->customer->name,
            'customer_phone' => '090-1234-5678',
            'customer_email' => $this->customer->email,
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('reservations.store', $this->store), $reservationData);

        $response->assertRedirect(route('stores.show', $this->store));

        $this->assertDatabaseHas('reservations', [
            'store_id' => $this->store->id,
            'customer_id' => $this->customer->id,
            'customer_email' => $this->customer->email,
            'status' => 'pending',
        ]);
    }

    public function test_reservation_requires_valid_data(): void
    {
        $response = $this->post(route('reservations.store', $this->store), []);

        $response->assertSessionHasErrors([
            'reservation_date',
            'reservation_time',
            'number_of_people',
            'customer_name',
            'customer_phone',
            'customer_email',
        ]);
    }

    public function test_reservation_date_must_be_in_future(): void
    {
        $reservationData = [
            'reservation_date' => now()->subDays(1)->format('Y-m-d'),
            'reservation_time' => '18:00',
            'number_of_people' => 4,
            'customer_name' => 'John Doe',
            'customer_phone' => '090-1234-5678',
            'customer_email' => 'john@example.com',
        ];

        $response = $this->post(route('reservations.store', $this->store), $reservationData);

        $response->assertSessionHasErrors(['reservation_date']);
    }

    public function test_number_of_people_must_be_positive(): void
    {
        $reservationData = [
            'reservation_date' => now()->addDays(3)->format('Y-m-d'),
            'reservation_time' => '18:00',
            'number_of_people' => 0,
            'customer_name' => 'John Doe',
            'customer_phone' => '090-1234-5678',
            'customer_email' => 'john@example.com',
        ];

        $response = $this->post(route('reservations.store', $this->store), $reservationData);

        $response->assertSessionHasErrors(['number_of_people']);
    }

    public function test_can_view_reservation_details(): void
    {
        $reservation = Reservation::factory()->create([
            'store_id' => $this->store->id,
        ]);

        $response = $this->get(route('reservations.show', $reservation));

        $response->assertStatus(200);
        $response->assertViewIs('public.reservations.show');
    }

    public function test_store_owner_can_confirm_reservation(): void
    {
        $reservation = Reservation::factory()->create([
            'store_id' => $this->store->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->storeOwner)
            ->post(route('store.reservations.confirm', $reservation));

        $response->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'confirmed',
        ]);

        $reservation->refresh();
        $this->assertNotNull($reservation->confirmed_at);
    }

    public function test_store_owner_can_cancel_reservation(): void
    {
        $reservation = Reservation::factory()->create([
            'store_id' => $this->store->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->storeOwner)
            ->post(route('store.reservations.cancel', $reservation));

        $response->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'cancelled',
        ]);

        $reservation->refresh();
        $this->assertNotNull($reservation->cancelled_at);
    }

    public function test_store_owner_can_complete_reservation(): void
    {
        $reservation = Reservation::factory()->confirmed()->create([
            'store_id' => $this->store->id,
        ]);

        $response = $this->actingAs($this->storeOwner)
            ->post(route('store.reservations.complete', $reservation));

        $response->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'completed',
        ]);
    }

    public function test_store_owner_cannot_manage_other_stores_reservations(): void
    {
        $otherCategory = Category::factory()->create();
        $otherArea = Area::factory()->create();
        $otherStore = Store::factory()->create([
            'category_id' => $otherCategory->id,
            'area_id' => $otherArea->id,
            'status' => 'active',
        ]);

        $reservation = Reservation::factory()->create([
            'store_id' => $otherStore->id,
        ]);

        $response = $this->actingAs($this->storeOwner)
            ->post(route('store.reservations.confirm', $reservation));

        $response->assertStatus(403);
    }

    public function test_store_staff_can_view_reservations(): void
    {
        $storeStaff = User::factory()->create([
            'role' => 'store_staff',
            'status' => 'active',
            'store_id' => $this->store->id,
        ]);

        Reservation::factory()->count(3)->create([
            'store_id' => $this->store->id,
        ]);

        $response = $this->actingAs($storeStaff)
            ->get(route('store.reservations.index'));

        $response->assertStatus(200);
        $response->assertViewIs('store.reservations.index');
    }

    public function test_store_staff_can_confirm_reservation(): void
    {
        $storeStaff = User::factory()->create([
            'role' => 'store_staff',
            'status' => 'active',
            'store_id' => $this->store->id,
        ]);

        $reservation = Reservation::factory()->create([
            'store_id' => $this->store->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($storeStaff)
            ->post(route('store.reservations.confirm', $reservation));

        $response->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_reservation_sends_notification_emails(): void
    {
        Mail::fake();

        $reservationData = [
            'reservation_date' => now()->addDays(3)->format('Y-m-d'),
            'reservation_time' => '18:00',
            'number_of_people' => 4,
            'customer_name' => 'John Doe',
            'customer_phone' => '090-1234-5678',
            'customer_email' => 'john@example.com',
        ];

        $this->post(route('reservations.store', $this->store), $reservationData);

        // Check that emails were sent
        Mail::assertSent(\App\Mail\ReservationCreated::class);
        if ($this->store->email) {
            Mail::assertSent(\App\Mail\ReservationNotification::class);
        }
    }

    public function test_can_view_upcoming_reservations(): void
    {
        // Create upcoming reservations
        Reservation::factory()->create([
            'store_id' => $this->store->id,
            'status' => 'confirmed',
            'reservation_date' => now()->addDays(5),
        ]);

        // Create past reservation
        Reservation::factory()->create([
            'store_id' => $this->store->id,
            'status' => 'completed',
            'reservation_date' => now()->subDays(5),
        ]);

        $response = $this->actingAs($this->storeOwner)
            ->get(route('store.reservations.index'));

        $response->assertStatus(200);
    }
}
