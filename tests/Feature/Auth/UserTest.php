<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }
    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'user' => ['id', 'name', 'email'],
                 ]);
    }

    public function test_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/v1/users/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Logged out successfully']);
    }

    public function test_create_user_with_valid_data()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ];

        $user = $this->userService->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['email'], $user->email);
    }


    /** 
     * Test creating a user with invalid data (missing email).
     */
    public function test_create_user_with_invalid_data()
    {
        $data = [
            'name' => 'John Doe',
            'password' => 'password123',
        ];
        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->userService->createUser($data);
    }

    /** 
     * Test retrieving an existing user by ID.
     */
    public function test_get_user_by_id()
    {
        $user = User::factory()->create();
        $retrievedUser = $this->userService->getUserById($user);

        $this->assertEquals($user->id, $retrievedUser->id);
    }

    /** 
     * Test updating a user with valid data.
     */
    public function test_update_user_with_valid_data()
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updatedemail@example.com',
        ];

        $updatedUser = $this->userService->updateUser($updateData, $user);

        $this->assertEquals($updateData['name'], $updatedUser->name);
        $this->assertEquals($updateData['email'], $updatedUser->email);
    }

    /** 
     * Test deleting a user.
     */
    public function test_delete_user()
    {
        $user = User::factory()->create();

        $this->userService->deleteUser($user);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

}
