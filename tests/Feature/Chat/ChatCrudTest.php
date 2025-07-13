<?php

namespace Tests\Feature\Chat;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;

class ChatCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_user_can_create_chat(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chats', [
            'message' => 'Hello, AI!',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'chat_id',
                'response',
            ]);
    }

    public function test_user_can_list_their_chats()
    {
        Chat::factory()->count(2)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->getJson('/api/chats');

        $response->assertOk()
                 ->assertJsonCount(2);
    }

    public function test_user_can_send_message_to_chat()
    {
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->postJson("/api/chats/{$chat->id}/message", [
            'message' => 'Follow-up message',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'chat_id',
                'response',
            ]);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => 'Follow-up message',
        ]);
    }

    public function test_user_can_view_a_chat_with_messages()
    {
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
        ]);

        Message::factory()->count(3)->create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => 'Test',
        ]);

        $response = $this->getJson("/api/chats/{$chat->id}");

        $response->assertOk()
                 ->assertJsonStructure([
                     'chat_id',
                     'title',
                     'dialogue',
                 ])
                 ->assertJsonPath('chat_id', $chat->id);
    }

    public function test_user_can_delete_chat(): void
    {
        $user = User::factory()->create();
        $chat = Chat::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/chats/{$chat->id}");

        $response->assertNoContent(); // 204

        $this->assertDatabaseMissing('chats', [
            'id' => $chat->id,
        ]);
    }
}
