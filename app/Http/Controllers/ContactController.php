<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;

class ContactController extends Controller
{
    public function checkUserPhone(Request $request) 
    {
        // Verifica o telefone do usuário e retorna as informações
        $phone = $request->input('phone');

        $user = User::where('phone', $phone)->first();

        if ($user) {
            return response()->json($user, 200);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    public function add(AddContactRequest $request)
    {
        // Adiociona o contato do usuário
        $user = Auth::user();

        $contactUser = User::where('phone', $request->cleaned_phone)->first();

        Contact::create([
            'user_id' => $user->id,
            'contact_id' => $contactUser->id,
        ]);
    }

    public function getContacts()
    {
        $user = Auth::user();

        // Pega todos os contatos do usuário logado, incluindo as conversas onde ambos estão
        $contacts = $user->contactUsers()
            ->with(['conversations' => function ($query) use ($user) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }])
            ->get()
            ->map(function ($contact) use ($user) {
                // Pega a conversa onde o contato e o usuário logado estão
                $conversation = $contact->conversations->first(function ($conversation) use ($user) {
                    return $conversation->users->contains('id', $user->id);
                });

                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'phone' => $contact->phone,
                    'profile_photo_path' => $contact->profile_photo_path,
                    'conversation_id' => optional($conversation)->id,
                ];
            });

        return response()->json($contacts);
    }
}
