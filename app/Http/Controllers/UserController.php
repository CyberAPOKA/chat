<?php

namespace App\Http\Controllers;

use App\Events\NameUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\ProfilePhotoUpdated;
use App\Models\Conversation;
use Faker\Guesser\Name;

class UserController extends Controller
{
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->profile_photo_path = $path;
        $user->save();

        $profilePhotoUrl = Storage::url($path);

        broadcast(new ProfilePhotoUpdated($user->id, $profilePhotoUrl));

        return response()->json(['profile_photo_url' => $profilePhotoUrl]);
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->name = $request->input('name');
        $user->save();

        broadcast(new NameUpdated($user->id, $user->name));

        return response()->json(['name' => $user->name]);
    }

    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_ids' => 'required|array|min:1',
            'contact_ids.*' => 'exists:users,id'
        ]);

        $conversation = Conversation::create([
            'name' => $request->name,
            'is_group' => true,
        ]);

        // Adiciona o criador do grupo à conversa
        $conversation->users()->attach(auth()->id());

        // Adiciona os contatos selecionados à conversa
        $conversation->users()->attach($request->contact_ids);

        return response()->json(['message' => 'Grupo criado com sucesso', 'conversation' => $conversation]);
    }
}
