<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvestmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('investor')->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'intended_amount' => ['required', 'numeric', 'min:1', 'max:1000000000'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'intended_amount.required' => 'Le montant proposé est obligatoire.',
            'intended_amount.numeric' => 'Le montant doit être un nombre.',
            'intended_amount.min' => 'Le montant doit être au moins 1.',
            'message.string' => 'Le message doit être un texte.',
            'message.max' => 'Le message ne doit pas dépasser 1000 caractères.',
        ];
    }
}
