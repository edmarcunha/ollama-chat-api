<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_user_can_be_created(): void
    {
        $this->authenticate();

        $response = $this->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'secret123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'Test User')
            ->assertJsonPath('email', 'user@example.com');

        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
        ]);
    }

    public function test_user_list_can_be_retrieved(): void
    {
        $this->authenticate();

        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertOk()
            ->assertJsonCount(4); // 3 criados + 1 autenticado
    }

    public function test_user_can_be_shown(): void
    {
        $this->authenticate();

        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertOk()
            ->assertJsonPath('id', $user->id)
            ->assertJsonPath('email', $user->email);
    }

    public function test_user_can_be_updated(): void
    {
        $this->authenticate();

        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertOk()
            ->assertJsonPath('user.name', 'Updated Name')
            ->assertJsonPath('user.email', 'updated@example.com');
    }

    public function test_user_can_be_soft_deleted_and_scheduled(): void
    {
        $this->authenticate();

        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertOk()
            ->assertJsonStructure(['message', 'scheduled_for']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $this->assertNotNull($user->fresh()->scheduled_for_deletion_at);
    }
}
