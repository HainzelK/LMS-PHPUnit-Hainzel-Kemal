<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\DB;

class AuthTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    /** @test */
    public function it_can_register_a_user()
    {
        // Arrange
        $data = [
            'name' => 'Test User',
            'phone_number' => '1234567890',
            'password' => 'password',
        ];

        // Act
        $response = $this->post('/register', $data);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'phone_number' => '1234567890',
        ]);
    }

    /** @test */
    public function it_can_login_a_user_as_admin()
    {
        // Arrange: Create a user and mark them as admin
        $user = User::factory()->create([
            'phone_number' => '1234567890',
            'password' => Hash::make('password'),
        ]);

        DB::table('admin')->insert(['user_id' => $user->id]);

        // Act: Attempt to log in
        $response = $this->post('/login', [
            'phone_number' => '1234567890',
            'password' => 'password',
        ]);

        // Assert: Check redirection to admin dashboard
        $response->assertRedirect(route('admin.librarians.index'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotEmpty(Session::get('auth_token'));
    }

    /** @test */
    public function it_can_login_a_user_as_librarian()
    {
        // Arrange: Create a user and mark them as librarian
        $user = User::factory()->create([
            'phone_number' => '0987654321',
            'password' => Hash::make('password'),
        ]);

        DB::table('librarians')->insert(['user_id' => $user->id]);

        // Act: Attempt to log in
        $response = $this->post('/login', [
            'phone_number' => '0987654321',
            'password' => 'password',
        ]);

        // Assert: Check redirection to librarian dashboard
        $response->assertRedirect(route('books.index'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotEmpty(Session::get('auth_token'));
    }

    /** @test */
    public function it_fails_login_with_invalid_credentials()
    {
        // Arrange: Create a user
        User::factory()->create([
            'phone_number' => '1234567890',
            'password' => Hash::make('password'),
        ]);

        // Act: Attempt to log in with incorrect credentials
        $response = $this->post('/login', [
            'phone_number' => '1234567890',
            'password' => 'wrongpassword',
        ]);

        // Assert: Check for error message
        $response->assertSessionHasErrors(['login_error' => 'Invalid phone number or password.']);
        $this->assertGuest();
    }

    /** @test */
    public function it_can_logout_a_user()
    {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act: Log out the user
        $response = $this->post('/logout');

        // Assert: Check redirection and unauthenticated state
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
