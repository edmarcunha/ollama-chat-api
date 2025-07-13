<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cloudstudio\Ollama\Facades\Ollama;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\ChatService;

class ChatController extends Controller
{

    use AuthorizesRequests;

    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index()
    {
        return Auth::user()->chats()->with('messages')->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $response = $this->chatService->createChat($request->message);

        return response()->json($response);
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $response = $this->chatService->handleMessage($chat, $request->message);

        return response()->json($response);
    }

    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);

        $chat->load('messages');
        // dialog structure
        $dialogue = $chat->messages
        ->sortBy('created_at')
        ->map(function ($message) {
            return [
                'id' => $message->id,               // message ID
                'role' => $message->role,           // 'user' ou 'assistant'
                'content' => $message->content,     // message content
                'timestamp' => $message->created_at->toDateTimeString(),
            ];
        })
        ->values();

        return response()->json([
            'chat_id' => $chat->id,
            'title' => $chat->title,
            'dialogue' => $dialogue,
        ])->setStatusCode(200);
    }

    public function destroy(Chat $chat)
    {
        $this->authorize('delete', $chat);

        $chat->delete();

        return response()->json([
            'message' => 'Chat deleted successfully.',
        ])->setStatusCode(204);
    }
}
