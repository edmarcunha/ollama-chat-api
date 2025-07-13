<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Cloudstudio\Ollama\Facades\Ollama;

class ChatService
{
    public function createChat(string $message): array
    {
        $chat = Chat::create([
            'user_id' => Auth::id(),
            'title' => $this->generateTitle($message),
        ]);

        return $this->handleMessage($chat, $message);
    }

    public function handleMessage(Chat $chat, string $message): array
    {
        // Save the user's message
        $chat->messages()->create([
            'role' => 'user',
            'content' => $message,
        ]);

        // Get the last 10 messages for context
        $history = $chat->messages()
            ->latest('id')
            ->take(10)
            ->get()
            ->reverse()
            ->map(fn ($msg) => [
                'role' => $msg->role,
                'content' => $msg->content,
            ])
            ->values()
            ->toArray();

        // Send the message to the AI model
        $response = Ollama::model('llama3.2:1b')->chat($history);

        $assistantContent = $response['message']['content'] ?? 'Error processing answer.';

        // Save the assistant's response
        $chat->messages()->create([
            'role' => 'assistant',
            'content' => $assistantContent,
        ]);

        return [
            'chat_id' => $chat->id,
            'response' => $assistantContent,
        ];
    }

    private function generateTitle(string $message): string
    {
        // Gera um título com base na primeira pergunta enviada
        $response = Ollama::agent('Create a short title for this conversation based on the following question. Return only the title.')
            ->prompt($message)
            ->model('llama3.2:1b')
            ->ask();

        return $response['response'] ?? 'New Chat';
    }
}
