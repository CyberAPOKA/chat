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

        // Busca todos os contatos do usuário logado
        $contacts = $user->contactUsers()->with('contactUsers')->get();

        return response()->json($contacts);
    }
}
