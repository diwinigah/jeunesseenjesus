<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Rules\RecaptchaRule;
use App\Services\CampEditionService;
use App\Services\RegistrationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(RegistrationService::class)->isRegistrationOpen();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $edition = app(RegistrationService::class)->getOpenEdition();

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9\s\-\(\)]{7,20}$/'],
            'whatsapp_phone' => ['nullable', 'string', 'regex:/^\+?[0-9\s\-\(\)]{7,20}$/'],
            'city' => ['nullable', 'string', 'max:150'],
            'edition_section_id' => [
                'nullable',
                'integer',
                Rule::exists('edition_sections', 'id')
                    ->where('camp_edition_id', $edition?->getKey())
                    ->where('is_active', true),
            ],
            'days_presence' => [
                $edition?->show_days_presence ? 'required' : 'nullable',
                'array',
                'distinct',
            ],
            'days_presence.*' => [
                'string',
                'in:jour_1,jour_2,jour_3,jour_4,jour_5,jour_6',
            ],

            'children_count'  => ['nullable', 'integer', 'min:0', 'max:20'],

            'bus_departure' => [
                $edition?->show_bus_departure ? 'required' : 'nullable',
                'boolean',
            ],

            'participant_type' => [
                $edition?->show_participant_type ? 'required' : 'nullable',
                Rule::in(['eleve', 'etudiant', 'adulte']),
            ],

            'g-recaptcha-response' => ['required_with:g-recaptcha-response', new RecaptchaRule()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Le prenom est obligatoire.',
            'first_name.max' => 'Le prenom ne peut pas depasser :max caracteres.',
            'last_name.required' => 'Le nom est obligatoire.',
            'last_name.max' => 'Le nom ne peut pas depasser :max caracteres.',
            'gender.required' => 'Le genre est obligatoire.',
            'gender' => 'Le genre selectionne est invalide.',
            'phone.required' => 'Le numero de telephone est obligatoire.',
            'phone.regex' => 'Le numero de telephone n\'est pas valide. Utilisez uniquement des chiffres avec indicatif si necessaire (ex: +228 90 00 00 00).',
            'whatsapp_phone.regex' => 'Le numero WhatsApp n\'est pas valide.',
            'city.max' => 'La ville ne peut pas depasser :max caracteres.',
            'edition_section_id.exists' => 'La section selectionnee est indisponible.',
            'days_presence.required'    => 'Veuillez indiquer vos jours de présence.',
            'bus_departure.required'    => 'Veuillez indiquer si vous partez avec le bus.',
            'participant_type.required' => 'Veuillez indiquer votre statut (Élève, Étudiant ou Adulte).',
            'g-recaptcha-response.required_with' => 'Veuillez confirmer que vous n\'êtes pas un robot.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'prenom',
            'last_name' => 'nom',
            'gender' => 'genre',
            'phone' => 'telephone',
            'whatsapp_phone' => 'WhatsApp',
            'city' => 'ville',
            'edition_section_id' => 'section',
        ];
    }
}
