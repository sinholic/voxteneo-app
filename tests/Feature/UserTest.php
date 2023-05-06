<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_new_user()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!!',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $this->assertTrue(Hash::check('password123', User::where('email', 'john@example.com')->first()->password));
    }
    
    /** @test */
    public function can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);
    
        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);
    
        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Login successful']);
        $response->assertJsonFragment(['email' => 'jane@example.com']);
    }
    
    /** @test */
    public function cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);
    
        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'wrong_password',
        ]);
    
        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Invalid login credentials']);
    }
    
    /** @test */
    public function can_get_user_with_valid_token()
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);
    
        $token = $user->createToken('test_token')->plainTextToken;
    
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->get('/api/user');
    
        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => 'jane@example.com']);
    }
    
    /** @test */
    public function cannot_get_user_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer invalid_token",
        ])->get('/api/user');
    
        $response->assertStatus(401);
    }
}