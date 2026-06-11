<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartnerRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'contact_name' => ['required', 'string', 'max:255'],
            'organization_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^\+?[0-9\s\-\(\)]{7,20}$/'],
            'type' => ['nullable', 'in:church,company,association,individual,other'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact_name.required' => 'Le nom du contact est obligatoire.',
            'contact_name.string' => 'Le nom du contact doit être un texte.',
            'contact_name.max' => 'Le nom du contact ne doit pas dépasser 255 caractères.',
            'organization_name.required' => 'Le nom de l\'organisation est obligatoire.',
            'organization_name.string' => 'Le nom de l\'organisation doit être un texte.',
            'organization_name.max' => 'Le nom de l\'organisation ne doit pas dépasser 255 caractères.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'adresse email ne doit pas dépasser 255 caractères.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.regex' => 'Le numéro de téléphone doit être valide (7-20 caractères).',
            'type.in' => 'Le type doit être l\'une des valeurs autorisées.',
            'website_url.url' => 'L\'URL du site doit être valide.',
            'website_url.max' => 'L\'URL du site ne doit pas dépasser 500 caractères.',
            'message.string' => 'Le message doit être un texte.',
            'message.max' => 'Le message ne doit pas dépasser 2000 caractères.',
        ];
    }
}
