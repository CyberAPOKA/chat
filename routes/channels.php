<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversations.{conversationId}', function ($user, $conversationId) {
    \Log::info('User attempting to join channel:', ['user_id' => $user->id, 'conversation_id' => $conversationId]);
    return $user->conversations->contains($conversationId);
});

