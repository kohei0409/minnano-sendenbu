<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_registration_form(): void
    {
        $response = $this->get(route('customer.register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.customer.register');
    }

    public function test_customer_can_register(): void
    {
        $customerData = [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('customer.register'), $customerData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
        ]);

        $customer = Customer::where('email', 'customer@example.com')->first();
        $this->assertTrue(Hash::check('password123', $customer->password));
    }

    public function test_customer_registration_requires_valid_data(): void
    {
        $response = $this->post(route('customer.register'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_customer_registration_requires_unique_email(): void
    {
        $existingCustomer = Customer::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $customerData = [
            'name' => 'Test Customer',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('customer.register'), $customerData);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_customer_registration_requires_password_confirmation(): void
    {
        $customerData = [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ];

        $response = $this->post(route('customer.register'), $customerData);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_customer_can_view_login_form(): void
    {
        $response = $this->get(route('customer.login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.customer.login');
    }

    public function test_customer_can_login_with_correct_credentials(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('customer.login'), [
            'email' => 'customer@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_customer_cannot_login_with_incorrect_password(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('customer.login'), [
            'email' => 'customer@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('customer');
    }

    public function test_customer_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->post(route('customer.login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('customer');
    }

    public function test_customer_can_logout(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->post(route('customer.logout'));

        $response->assertRedirect('/');
        $this->assertGuest('customer');
    }

    public function test_authenticated_customer_cannot_view_login_form(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->get(route('customer.login'));

        $response->assertRedirect('/');
    }

    public function test_authenticated_customer_cannot_view_registration_form(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->get(route('customer.register'));

        $response->assertRedirect('/');
    }

    public function test_customer_can_view_password_reset_request_form(): void
    {
        $response = $this->get(route('customer.password.request'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.customer.forgot-password');
    }

    public function test_customer_can_request_password_reset_link(): void
    {
        Notification::fake();

        $customer = Customer::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $response = $this->post(route('customer.password.email'), [
            'email' => 'customer@example.com',
        ]);

        $response->assertSessionHas('status');
    }

    public function test_customer_cannot_request_password_reset_with_invalid_email(): void
    {
        $response = $this->post(route('customer.password.email'), [
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_customer_can_view_password_reset_form(): void
    {
        $token = 'fake-reset-token';

        $response = $this->get(route('customer.password.reset', $token));

        $response->assertStatus(200);
        $response->assertViewIs('auth.customer.reset-password');
    }

    public function test_customer_can_reset_password(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $token = Password::broker('customers')->createToken($customer);

        $response = $this->post(route('customer.password.update'), [
            'token' => $token,
            'email' => 'customer@example.com',
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ]);

        $response->assertRedirect(route('customer.login'));

        $customer->refresh();
        $this->assertTrue(Hash::check('new_password123', $customer->password));
    }

    public function test_customer_cannot_reset_password_with_invalid_token(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $response = $this->post(route('customer.password.update'), [
            'token' => 'invalid-token',
            'email' => 'customer@example.com',
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_customer_password_reset_requires_password_confirmation(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $token = Password::broker('customers')->createToken($customer);

        $response = $this->post(route('customer.password.update'), [
            'token' => $token,
            'email' => 'customer@example.com',
            'password' => 'new_password123',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_inactive_customer_cannot_login(): void
    {
        $customer = Customer::factory()->inactive()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('customer.login'), [
            'email' => 'customer@example.com',
            'password' => 'password123',
        ]);

        // Depending on your implementation, this might redirect or show an error
        // Adjust the assertion based on your actual implementation
        $this->assertGuest('customer');
    }

    public function test_customer_login_requires_email_and_password(): void
    {
        $response = $this->post(route('customer.login'), []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_customer_stays_on_intended_page_after_login(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Try to access a protected route
        $this->get(route('customer.favorites.index'));

        // Login
        $response = $this->post(route('customer.login'), [
            'email' => 'customer@example.com',
            'password' => 'password123',
        ]);

        // Should redirect to the intended page or home
        $response->assertRedirect();
    }
}
