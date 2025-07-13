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
        // Salva a mensagem do usuário
        $chat->messages()->create([
            'role' => 'user',
            'content' => $message,
        ]);

        // Obtém o histórico de até 10 mensagens
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

        // Envia para o modelo
        $response = Ollama::model('llama3.2:1b')->chat($history);

        $assistantContent = $response['message']['content'] ?? 'Erro ao gerar resposta.';

        // Salva a resposta
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
        $response = Ollama::agent('Crie um título curto para essa conversa com base na pergunta a seguir. Retorne apenas o título.')
            ->prompt($message)
            ->model('llama3.2:1b')
            ->ask();

        return $response['response'] ?? 'Nova conversa';
    }
}
