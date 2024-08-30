<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use App\Models\User;
use App\Models\Contact;

class AddContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            
            $user = Auth::user();

            // Remove caracteres especiais
            $cleanedPhone = preg_replace('/[^0-9]/', '', $this->input('phone'));

            // Verifica se o número é do próprio usuário
            if ($cleanedPhone === $user->phone) {
                $validator->errors()->add('phone', 'Você não pode adicionar seu próprio número.');
            }

            // Verifica se o número existe
            $contactUser = User::where('phone', $cleanedPhone)->first();

            if (!$contactUser) {
                $validator->errors()->add('phone', 'O número de telefone não está associado a nenhum usuário.');
            }

            // Verifica se o usuário já tem esse contato
            $existingContact = Contact::where('user_id', $user->id)
                ->where('contact_id', $contactUser->id ?? null)
                ->exists();

            if ($existingContact) {
                $validator->errors()->add('phone', 'Este usuário já está nos seus contatos.');
            }

            // Guarda o número no request para usar no controller
            $this->merge(['cleaned_phone' => $cleanedPhone]);
        });
    }
}
