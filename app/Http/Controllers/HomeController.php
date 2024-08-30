<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::with(['users' => function ($query) use ($user) {
            $query->where('user_id', '!=', $user->id); // Carrega apenas os outros usuários
        }])
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['messages' => function ($query) {
                $query->latest()->take(1); // Carrega apenas a última mensagem de cada conversa
            }])
            ->take(20)
            ->get();

        return Inertia::render('Welcome', [
            'conversations' => $conversations
        ]);
    }


    public function loadConversation($id)
    {
        $user = Auth::user();

        // Carrega a conversa específica com suas mensagens e usuários
        $conversation = Conversation::with(['users', 'messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($id);

        return response()->json($conversation);
    }
}
