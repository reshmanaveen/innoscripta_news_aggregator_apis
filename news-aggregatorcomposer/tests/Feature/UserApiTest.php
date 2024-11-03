<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registering a new user.
     */
    public function test_registers_a_new_user(): void
    {
        $data = [
            'name' => 'Reshma N',
            'email' => 'reshmabelman@gmail.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User registered successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'reshmabelman@gmail.com',
        ]);
    }

    /**
     * Test failing to register with invalid data.
     */
    public function test_fails_to_register_with_invalid_data(): void
    {
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422);
    }

    /**
     * Test logging in a user with valid credentials.
     */
    public function test_logs_in_a_user_with_valid_credentials(): void
    {
        // Create the user with the correct password
        $user = User::create([
            'name' => 'Reshma N',
            'email' => 'reshmabelman@gmail.com',
            'password' => Hash::make('password123'), // Make sure the password matches
        ]);
    
        $data = [
            'email' => 'reshmabelman@gmail.com',
            'password' => 'password123', // Use the same password
        ];
    
        $response = $this->postJson('/api/login', $data);
    
        // Assert that the response status is 200
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'token' => $response->json('data.token'), // Check for the token in the data
                ],
            ]);
    }
    /**
     * Test failing to log in with invalid credentials.
     */
    public function test_fails_to_log_in_with_invalid_credentials(): void
    {
        $data = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }

    /**
     * Test failing to send a reset link to an unregistered email.
     */
    public function test_fails_to_send_reset_link_to_unregistered_email(): void
    {
        $response = $this->postJson('/api/password/email', [
            'email' => 'unregistered@example.com',
        ]);

        $response->assertStatus(422);
    }   
}
