<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        // Valida o conteúdo da mensagem
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Cria a nova mensagem
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Atualiza o campo updated_at da conversa
        $conversation->touch();

        // Retorna a mensagem recém-criada
        return response()->json($message);
    }
}